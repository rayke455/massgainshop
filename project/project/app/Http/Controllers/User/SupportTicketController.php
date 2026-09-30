<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketMessage;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SupportTicketController extends Controller
{
    use Upload, Notify;

    protected object $user;
    protected string $theme;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->all();
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->when(!empty($search['ticket_id']), function ($query) use ($search) {
                $query->where('ticket', 'like', '%' . $search['ticket_id'] . '%');
            })
            ->when(!empty($search['subject']), function ($query) use ($search) {
                $query->where('subject', 'like', '%' . $search['subject'] . '%');
            })
            ->when(!empty($search['status']), function ($query) use ($search) {
                $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view(template() . 'user.support_ticket.index', compact('tickets'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view(template() . 'user.support_ticket.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $random = rand(100000, 999999);
        $this->newTicketValidation($request);
        $ticket = $this->saveTicket($request, $random);
        $message = $this->saveMsgTicket($request, $ticket);

        if (!empty($request->images)) {
            $numberOfAttachments = count($request->images);
            for ($i = 0; $i < $numberOfAttachments; $i++) {
                if ($request->hasFile('images.' . $i)) {
                    $file = $request->file('images.' . $i);
                    $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', '70');
                    if (empty($supportFile['path'])) {
                        throw new \Exception('File could not be uploaded.');
                    }
                    $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                }
            }
        }

        $msg = [
            'user' => optional($ticket->user)->username,
            'ticket_id' => $ticket->ticket
        ];
        $action = [
            "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
            "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
            "link" => route('admin.ticket.view', $ticket->id),
            "icon" => "fas fa-ticket-alt text-white"
        ];
        $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);
        $this->adminMail('SUPPORT_TICKET_CREATE', $msg);
        return redirect()->route('user.ticket.list')->with('success', 'Your ticket has been pending.');
    }

    public function ticketView($ticketId): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $ticket = SupportTicket::where('ticket', $ticketId)->latest()->with('messages')->firstOrFail();
        $user = auth()->user();
        return view(template() . 'user.support_ticket.view', compact('ticket', 'user'));
    }

    public function reply(Request $request, $id)
    {
        if ($request->reply_ticket == 1) {
            $images = $request->file('images');
            $allowedExtensions = array('jpg', 'png', 'jpeg', 'pdf');
            $request->validate([
                'images' => [
                    'max:4096',
                    function ($fail) use ($images, $allowedExtensions) {
                        $this->imageValidationCheck($images, $allowedExtensions);
                    },
                ],
                'message' => 'required',
            ]);

            try {
                $ticket = SupportTicket::findOrFail($id);
                $ticket->update([
                    'status' => 2,
                    'last_reply' => Carbon::now()
                ]);

                $message = SupportTicketMessage::create([
                    'support_ticket_id' => $ticket->id,
                    'message' => $request->message
                ]);

                if (!empty($request->attachments)) {
                    $numberOfAttachments = count($request->attachments);
                    for ($i = 0; $i < $numberOfAttachments; $i++) {
                        if ($request->hasFile('attachments.' . $i)) {
                            $file = $request->file('attachments.' . $i);
                            $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', '70');
                            if (empty($supportFile['path'])) {
                                throw new \Exception('File could not be uploaded.');
                            }
                            $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                        }
                    }
                }

                $msg = [
                    'username' => optional($ticket->user)->username,
                    'ticket_id' => $ticket->ticket
                ];
                $action = [
                    "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
                    "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
                    "link" => route('admin.ticket.view', $ticket->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);
                return back()->with('success', 'Ticket has been replied');
            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }

        } elseif ($request->reply_ticket == 2) {
            $ticket = SupportTicket::findOrFail($id);
            $ticket->update([
                'status' => 3,
                'last_reply' => Carbon::now()
            ]);

            return back()->with('success', 'Ticket has been closed');
        }
        return back();
    }

    public function imageValidationCheck($images, $allowedExtensions): void
    {
        foreach ($images as $img) {
            $ext = strtolower($img->getClientOriginalExtension());
            if (($img->getSize() / 1000000) > 2) {
                throw ValidationException::withMessages(['images' => "Images MAX  2MB ALLOW!"]);
            }
            if (!in_array($ext, $allowedExtensions)) {
                throw ValidationException::withMessages(['images' => "Only png, jpg, jpeg, pdf images are allowed"]);
            }
        }
        if (count($images) > 5) {
            throw ValidationException::withMessages(['images' => "Maximum 5 images can be uploaded"]);
        }
    }


    public function download($ticket_id)
    {
        $attachment = SupportTicketAttachment::with('supportMessage', 'supportMessage.ticket')->findOrFail(decrypt($ticket_id));
        $file = $attachment->file;
        $full_path = getFile($attachment->driver, $file);
        $title = slug($attachment->supportMessage->ticket->subject) . '-' . $file;
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $full_path);
        return readfile($full_path);
    }


    public function newTicketValidation(Request $request): void
    {
        $images = $request->file('images');
        $allowedExtension = array('jpg', 'png', 'jpeg', 'pdf');

        $request->validate([
            'images' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($images, $allowedExtension) {
                    $this->imageValidationCheck($images, $allowedExtension);
                },
            ],
            'subject' => 'required|string|max:191',
            'message' => 'required|string|max:2000'
        ]);
    }


    public function saveTicket(Request $request, $random)
    {
        try {
            $ticket = SupportTicket::create([
                'user_id' => auth()->id(),
                'ticket' => $random,
                'subject' => $request->subject,
                'status' => 0,
                'last_reply' => Carbon::now(),
            ]);

            if (!$ticket) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return $ticket;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function saveMsgTicket(Request $request, $ticket)
    {
        try {
            $message = SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'message' => $request->message
            ]);

            if (!$message) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return $message;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function saveAttachment($message, $path, $driver): bool|\Illuminate\Http\RedirectResponse
    {
        try {
            $attachment = SupportTicketAttachment::create([
                'support_ticket_message_id' => $message->id,
                'file' => $path ?? null,
                'driver' => $driver ?? 'local',
            ]);

            if (!$attachment) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return true;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

}
