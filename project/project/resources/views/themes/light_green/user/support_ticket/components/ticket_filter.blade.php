<div class="offcanvas offcanvas-end" tabindex="-1" id="TicketFilter" aria-labelledby="TicketFilterLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="TicketFilterLabel">@lang("Ticket Filter")</h5>
        <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-light fa-arrow-right"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <form>
            <div class="row g-4">
                <div>
                    <label for="TicketID" class="form-label">@lang("Ticket ID")</label>
                    <input type="text" class="form-control" name="ticket_id" id="TicketID"
                           value="{{ request()->ticket_id }}"
                           autocomplete="off">
                </div>
                <div>
                    <label for="subject" class="form-label">@lang("Subject")</label>
                    <input type="text" class="form-control" name="subject" id="subject"
                           value="{{ request()->subject }}"
                           autocomplete="off">
                </div>
                <div id="formModal">
                    <label class="form-label">@lang("Status")</label>
                    <select class="modal-select" name="status">
                        <option value="">@lang("All Status")</option>
                        <option value="0" {{ request()->status == 0 ? 'Open' : '' }}>@lang("Open")</option>
                        <option value="1" {{ request()->status == 1 ? 'Answered' : '' }}>@lang("Answered")</option>
                        <option value="2" {{ request()->status == 2 ? 'Replied' : '' }}>@lang("Replied")</option>
                        <option value="3" {{ request()->status == 3 ? 'Closed' : '' }}>@lang("Closed")</option>
                    </select>
                </div>
                <div>
                    <label for="date" class="form-label">@lang("From Date")</label>
                    <input type="text" class="form-control flatpickr" name="from_date" id="date"
                           value="{{ request()->from_date }}"
                           autocomplete="off">
                </div>
                <div>
                    <label for="date" class="form-label">@lang("To Date")</label>
                    <input type="text" class="form-control flatpickr" name="to_date" id="date"
                           value="{{ request()->to_date }}"
                           autocomplete="off">
                </div>
                <div class="btn-area mt-4">
                    <button type="submit" class="cmn-btn rounded-1">@lang("Filter")</button>
                </div>
            </div>
        </form>
    </div>
</div>
