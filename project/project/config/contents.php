<?php
return [
    'light_green' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'short_description' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'video_link' => 'url',
                    'video_text' => 'text',
                    'image' => 'file',
                    'background_image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'heading.*' => 'required|string|min:1|max:250',
                    'short_description.*' => 'required|string|min:1|max:500',
                    'button_name.*' => 'required|string|min:1|max:50',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'video_link.*' => 'required|url|min:1|max:2000',
                    'video_text.*' => 'required|string|min:1|max:50',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                    'background_image' => 'file',
                ],
                'size' => [
                    'image' => '750x500',
                ],
            ],
            'image' => [
                'Hero Section' => 'assets/themes/light_green/img/section/hero.png',
            ],
        ],

        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'text',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'short_description.*' => 'required|string|min:1|max:200',
                    'icon.*' => 'required|string|max:50',
                ]
            ],
            'image' => [
                'Feature Section' => 'assets/themes/light_green/img/section/feature.png',
            ],
        ],

        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'description' => 'textarea',
                    'image_one' => 'file',
                    'image_two' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'description.*' => 'required|string|max:2000',
                    'image_one.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                    'image_two.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image_one' => '542x350',
                    'image_two' => '230x153',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:100',
                    'description.*' => 'required|string|max:2000',
                    'icon.*' => 'required|string|max:50',
                ]
            ],
            'image' => [
                'About Section' => 'assets/themes/light_green/img/section/about.png',
            ],
        ],

        'service' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                    'background_image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                    'background_image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'background_image' => '1440x1020',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'service_name' => 'text',
                    'description' => 'textarea',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'service_name.*' => 'required|string|max:100',
                    'description.*' => 'required|string|max:2000',
                    'icon.*' => 'required|string|max:50',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/light_green/img/section/service.png',
            ]
        ],

        'why_choose_us' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                    'image_one' => 'file',
                    'image_two' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                    'image_one.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                    'image_two.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image_one' => '542x361',
                    'image_two' => '230x148',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:100',
                    'description.*' => 'required|string|max:2000',
                    'icon.*' => 'required|string|max:50',
                ]
            ],
            'image' => [
                'Why Choose Us Section' => 'assets/themes/light_green/img/section/why_choose_us.png',
            ]
        ],

        'achievement' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'count' => 'number',
                    'title' => 'text',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'count.*' => 'required|integer',
                    'title.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:50',
                ]
            ],
            'image' => [
                'Achievement Section' => 'assets/themes/light_green/img/section/achievement.png',
            ]
        ],

        'how_it_works' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                    'image_one' => 'file',
                    'image_two' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                    'image_one.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                    'image_two.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image_one' => '575x400',
                    'image_two' => '260x186',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'text',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:500',
                    'icon.*' => 'required|string|max:50',
                ]
            ],
            'image' => [
                'How It Works Section' => 'assets/themes/light_green/img/section/how_it_works.png',
            ]
        ],

        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'address' => 'text',
                    'rating' => 'text',
                    'review' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'address.*' => 'required|string|max:100',
                    'rating.*' => 'required|integer',
                    'review.*' => 'required|string|max:500',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '65x65',
                ]
            ],
            'image' => [
                'Testimonial Section' => 'assets/themes/light_green/img/section/testimonial.png',
            ]
        ],

        'payment_partner' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'image' => 'file',
                ],
                'validation' => [
                    'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '145x120',
                ]
            ],
            'image' => [
                'Payment partner Section' => 'assets/themes/light_green/img/section/payment_partner.png',
            ]
        ],

        'blog' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_description' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Blog Section' => 'assets/themes/light_green/img/section/blog.png',
            ]
        ],

        'faq' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'question' => 'text',
                    'answer' => 'textarea',
                ],
                'validation' => [
                    'question.*' => 'required|string|max:500',
                    'answer.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/light_green/img/section/faq.png',
            ]
        ],

        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'short_description' => 'text',
                    'form_heading' => 'text',
                    'form_short_description' => 'text',
                    'phone' => 'text',
                    'email' => 'text',
                    'address' => 'text',
                    'map_link' => 'url',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'short_description.*' => 'required|string|max:500',
                    'form_heading.*' => 'required|string|max:2000',
                    'form_short_description.*' => 'required|string|max:500',
                    'phone.*' => 'required|string|max:50',
                    'email.*' => 'required|string|max:191',
                    'address.*' => 'required|string|max:100',
                    'map_link.*' => 'required|url|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Contact Section' => 'assets/themes/light_green/img/section/contact.png',
            ]
        ],

        'footer' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'text',
                    'news_letter_heading' => 'text',
                    'copyright' => 'text',
                    'overlay_image' => 'file',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:300',
                    'news_letter_heading.*' => 'required|string|max:100',
                    'copyright.*' => 'required|string|max:100',
                    'overlay_image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'overlay_image' => '1440x1024',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Footer Section' => 'assets/themes/light_green/img/section/footer.png',
            ]
        ],

        'login' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'background_image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|string|max:100',
                    'background_image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ]
            ],
            'size' => [
                'background_image' => '1920x862',
            ],
            'image' => [
                'Hero Section' => 'assets/themes/light_green/img/section/login.png',
            ],
        ],

        'register' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'background_image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|string|max:100',
                    'background_image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'background_image' => '1920x862',
                ],
            ],
            'image' => [
                'Register Section' => 'assets/themes/light_green/img/section/register.png',
            ]
        ],

        'how_to_mass_orders' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'description.*' => 'required|string|max:5000',
                ]
            ],
            'image' => [
                'How To Mass Order Section' => 'assets/themes/light_green/img/section/how_to_mass_orders.png',
            ]
        ],

        'child_panel_order_notes' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Child Panel Order Note Section' => 'assets/themes/light_green/img/section/child_panel_orders.png',
            ]
        ],

        'pwa_popup' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'domain_name' => 'text',
                    'short_description' => 'text',
                    'description' => 'text',
                    'icon_image' => 'file'
                ],
                'validation' => [
                    'title' => 'nullable',
                    'domain_name.*' => 'nullable',
                    'short_description.*' => 'nullable',
                    'description' => 'nullable',
                    'icon_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'carousel_image' => 'file'
                ],
                'validation' => [
                    'carousel_image.*' => 'required|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/images/preview/pwa.png'
        ],
    ],

    'dark_voilet' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'short_description' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'video_link' => 'url',
                    'video_text' => 'text',
                    'login_title' => 'text',
                    'login_subtitle' => 'text',
                    'background_image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'heading.*' => 'required|string|min:1|max:250',
                    'short_description.*' => 'required|string|min:1|max:500',
                    'button_name.*' => 'required|string|min:1|max:50',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'video_link.*' => 'required|url|min:1|max:2000',
                    'video_text.*' => 'required|string|min:1|max:50',
                    'login_title.*' => 'required|string|min:1|max:50',
                    'login_subtitle.*' => 'required|string|min:1|max:200',
                    'background_image' => 'file',
                ],
                'size' => [
                    'background_image' => '1920 × 1080',
                ]
            ],
            'image' => [
                'Hero Section' => 'assets/themes/dark_voilet/img/section/hero.png',
            ],
        ],

        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'text',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'short_description.*' => 'required|string|min:1|max:200',
                    'icon.*' => 'required|string|max:100',
                ]
            ],
            'image' => [
                'Feature Section' => 'assets/themes/dark_voilet/img/section/feature.png',
            ],
        ],

        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'description' => 'textarea',
                    'image' => 'file',

                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'description.*' => 'required|string|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '374x534',
                ]
            ],
            'image' => [
                'About Section' => 'assets/themes/dark_voilet/img/section/about.png',
            ],
        ],

        'service' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'short_description.*' => 'required|string|max:2000',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'icon' => 'icon',
                    'background_image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'icon.*' => 'required|string|max:100',
                    'background_image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'background_image' => '1920 × 1080',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/dark_voilet/img/section/service.png',
            ]
        ],

        'why_choose_us' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'short_description.*' => 'required|string|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:100',
                    'description.*' => 'required|string|max:2000',
                    'icon.*' => 'required|string|max:100',
                ],
            ],
            'image' => [
                'Why Choose Us Section' => 'assets/themes/dark_voilet/img/section/why_choose_us.png',
            ]
        ],

        'achievement' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'count' => 'number',
                    'title' => 'text',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'count.*' => 'required|integer',
                    'title.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                ]
            ],
            'image' => [
                'Achievement Section' => 'assets/themes/dark_voilet/img/section/achievement.png',
            ]
        ],

        'how_it_works' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'text',
                    'icon' => 'icon',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:500',
                    'icon.*' => 'required|string|max:100',
                ]
            ],
            'image' => [
                'How It Works Section' => 'assets/themes/dark_voilet/img/section/how_it_works.png',
            ]
        ],

        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'location' => 'text',
                    'rating' => 'text',
                    'review' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'location.*' => 'required|string|max:100',
                    'rating.*' => 'required|integer|not_in:0',
                    'review.*' => 'required|string|max:500',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '65x65',
                ]
            ],
            'image' => [
                'Testimonial Section' => 'assets/themes/dark_voilet/img/section/testimonial.png',
            ]
        ],

        'payment_partner' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'image' => 'file',
                ],
                'validation' => [
                    'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '145x120',
                ]
            ],
            'image' => [
                'Payment partner Section' => 'assets/themes/dark_voilet/img/section/payment_partner.png',
            ]
        ],

        'faq' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'heading' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'heading.*' => 'required|string|max:200',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'question' => 'text',
                    'answer' => 'textarea',
                ],
                'validation' => [
                    'question.*' => 'required|string|max:500',
                    'answer.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/dark_voilet/img/section/faq.png',
            ]
        ],

        'blog' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_description' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Blog Section' => 'assets/themes/dark_voilet/img/section/blog.png',
            ]
        ],

        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'short_description' => 'text',
                    'form_heading' => 'text',
                    'form_short_description' => 'text',
                    'phone' => 'text',
                    'email' => 'text',
                    'address' => 'text',
                    'map_link' => 'url',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'short_description.*' => 'required|string|max:500',
                    'form_heading.*' => 'required|string|max:2000',
                    'form_short_description.*' => 'required|string|max:500',
                    'phone.*' => 'required|string|max:50',
                    'email.*' => 'required|string|max:191',
                    'address.*' => 'required|string|max:100',
                    'map_link.*' => 'required|url|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Contact Section' => 'assets/themes/dark_voilet/img/section/contact.png',
            ]
        ],

        'footer' => [
            'single' => [
                'field_name' => [
                    'site_short_description' => 'text',
                    'news_letter_heading' => 'text',
                    'overlay_image' => 'file',
                ],
                'validation' => [
                    'site_short_description.*' => 'required|string|max:300',
                    'news_letter_heading.*' => 'required|string|max:100',
                    'overlay_image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'overlay_image' => '1440x1024',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Footer Section' => 'assets/themes/dark_voilet/img/section/footer.png',
            ]
        ],

        'login' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|string|max:100',
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/dark_voilet/img/section/login.png',
            ]
        ],

        'register' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|string|max:100',
                ]
            ],
            'image' => [
                'Register Section' => 'assets/themes/dark_voilet/img/section/register.png',
            ]
        ],

        'how_to_mass_orders' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'description.*' => 'required|string|max:10000',
                ]
            ],
            'image' => [
                'How To Mass Order Section' => 'assets/themes/dark_voilet/img/section/how_to_mass_orders.png',
            ]
        ],

        'child_panel_order_notes' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Child Panel Order Note Section' => 'assets/themes/dark_voilet/img/section/child_panel_orders.png',
            ]
        ],

        'pwa_popup' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'domain_name' => 'text',
                    'short_description' => 'text',
                    'description' => 'text',
                    'icon_image' => 'file'
                ],
                'validation' => [
                    'title' => 'nullable',
                    'domain_name.*' => 'nullable',
                    'short_description.*' => 'nullable',
                    'description' => 'nullable',
                    'icon_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'carousel_image' => 'file'
                ],
                'validation' => [
                    'carousel_image.*' => 'required|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/images/preview/pwa.png'
        ],
    ],

    'minimal' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '750x670',
                ]
            ],
            'image' => [
                'Hero Section' => 'assets/themes/minimal/images/section/hero.png',
            ],
        ],

        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                    'youtube_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'youtube_link.*' => 'required|url',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '492x461',
                ]
            ],
            'image' => [
                'About Section' => 'assets/themes/minimal/images/section/about.png',
            ],
        ],

        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'short_description.*' => 'required|string|min:1|max:200',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '84x80'
                ]
            ],
            'image' => [
                'Feature Section' => 'assets/themes/minimal/images/section/feature.png',
            ],
        ],

        'service' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'short_title.*' => 'required|max:2000'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '48x48',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/minimal/images/section/service.png',
            ]
        ],

        'how_it_works' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'short_title.*' => 'required|max:2000',
                    'short_description.*' => 'required|max:2000',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'icon' => 'icon',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'icon.*' => 'required|max:100',
                    'short_description.*' => 'required|max:5000',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/minimal/images/section/service.png',
            ]
        ],

        'counter' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'number_of_data' => 'text',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'number_of_data.*' => 'required|integer',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '50x50'
                ]
            ],
            'image' => [
                'Why Choose Us Section' => 'assets/themes/minimal/images/section/counter.png',
            ]
        ],

        'call_to_action' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '386x386'
                ]
            ],
            'image' => [
                'How It Works Section' => 'assets/themes/minimal/images/section/call_to_action.png',
            ]
        ],

        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:300',
                    'short_title.*' => 'required|max:400',
                    'short_description.*' => 'required|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'designation' => 'text',
                    'description' => 'textarea',
                    'image' => 'file'
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'designation.*' => 'required|max:2000',
                    'description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ]
            ],
            'image' => [
                'Testimonial Section' => 'assets/themes/minimal/images/section/testimonial.png',
            ]
        ],

        'payment_partner' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'image' => 'file',
                ],
                'validation' => [
                    'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '145x120',
                ]
            ],
            'image' => [
                'Payment partner Section' => 'assets/themes/minimal/images/section/payment_partner.png',
            ]
        ],

        'faq' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_details' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_details.*' => 'required|max:2000'
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/minimal/images/section/faq.png',
            ]
        ],

        'blog' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'short_title.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Blog Section' => 'assets/themes/minimal/images/section/blog.png',
            ]
        ],

        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'title' => 'text',
                    'sub_title' => 'text',
                    'address' => 'text',
                    'email' => 'text',
                    'phone' => 'text',
                    'footer_short_details' => 'textarea'
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'address.*' => 'required|max:2000',
                    'email.*' => 'required|max:2000',
                    'phone.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Contact Section' => 'assets/themes/minimal/images/section/contact.png',
            ]
        ],

        'footer' => [
            'single' => [
                'field_name' => [
                    'site_short_description' => 'text',
                    'phone' => 'text',
                    'email' => 'text',
                    'address' => 'text'
                ],
                'validation' => [
                    'site_short_description.*' => 'required|string|max:300',
                    'phone.*' => 'required|string|max:300',
                    'email.*' => 'required|string|max:300',
                    'address.*' => 'required|string|max:300',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Footer Section' => 'assets/themes/minimal/images/section/footer.png',
            ]
        ],

        'login' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/minimal/images/section/login.png',
            ]
        ],

        'register' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Register Section' => 'assets/themes/minimal/images/section/register.png',
            ]
        ],

        'forgot_password' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/minimal/images/section/forgot_password.png',
            ]
        ],

        'how_to_mass_orders' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'description.*' => 'required|string|max:10000',
                ]
            ],
            'image' => [
                'How To Mass Order Section' => 'assets/themes/minimal/images/section/how_to_mass_orders.png',
            ]
        ],

        'child_panel_order_notes' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Child Panel Order Note Section' => 'assets/themes/minimal/images/section/child_panel_orders.png',
            ]
        ],

        'pwa_popup' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'domain_name' => 'text',
                    'short_description' => 'text',
                    'description' => 'text',
                    'icon_image' => 'file'
                ],
                'validation' => [
                    'title' => 'nullable',
                    'domain_name.*' => 'nullable',
                    'short_description.*' => 'nullable',
                    'description' => 'nullable',
                    'icon_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'carousel_image' => 'file'
                ],
                'validation' => [
                    'carousel_image.*' => 'required|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/images/preview/pwa.png'
        ],
    ],

    'deep_blue' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '450x526',
                ]
            ],
            'image' => [
                'Hero Section' => 'assets/themes/deep_blue/img/section/hero.png',
            ],
        ],

        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '450x421',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '24x24'
                ]
            ],
            'image' => [
                'About Section' => 'assets/themes/deep_blue/img/section/about.png',
            ],
        ],

        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'short_description.*' => 'required|string|min:1|max:500',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '84x80'
                ]
            ],
            'image' => [
                'Feature Section' => 'assets/themes/deep_blue/img/section/feature.png',
            ],
        ],

        'service' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'short_title.*' => 'required|max:2000'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '48x48',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/deep_blue/img/section/service.png',
            ]
        ],

        'how_it_works' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:2000',
                    'short_description.*' => 'required|max:2000',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'icon' => 'icon',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'icon.*' => 'required|max:100',
                    'short_description.*' => 'required|max:5000',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/deep_blue/img/section/how_it_works.png',
            ]
        ],

        'counter' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'number_of_data' => 'text',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'number_of_data.*' => 'required|integer',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '50x50'
                ]
            ],
            'image' => [
                'Why Choose Us Section' => 'assets/themes/deep_blue/img/section/counter.png',
            ]
        ],

        'call_to_action' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '386x386'
                ]
            ],
            'image' => [
                'How It Works Section' => 'assets/themes/deep_blue/img/section/call_to_action.png',
            ]
        ],

        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:400',
                    'short_description.*' => 'required|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'designation' => 'text',
                    'description' => 'textarea',
                    'image' => 'file'
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'designation.*' => 'required|max:2000',
                    'description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ]
            ],
            'image' => [
                'Testimonial Section' => 'assets/themes/deep_blue/img/section/testimonial.png',
            ]
        ],

        'payment_partner' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'image' => 'file',
                ],
                'validation' => [
                    'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '145x120',
                ]
            ],
            'image' => [
                'Payment partner Section' => 'assets/themes/deep_blue/img/section/payment_partner.png',
            ]
        ],

        'faq' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/deep_blue/img/section/faqs.png',
            ]
        ],

        'blog' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Blog Section' => 'assets/themes/deep_blue/img/section/blog.png',
            ]
        ],

        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'title' => 'text',
                    'sub_title' => 'text',
                    'address' => 'text',
                    'email' => 'text',
                    'phone' => 'text',
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'address.*' => 'required|max:2000',
                    'email.*' => 'required|max:2000',
                    'phone.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Contact Section' => 'assets/themes/deep_blue/img/section/contact.png',
            ]
        ],

        'footer' => [
            'single' => [
                'field_name' => [
                    'site_short_description' => 'text',
                    'newsletter_heading' => 'text',
                ],
                'validation' => [
                    'site_short_description.*' => 'required|string|max:300',
                    'newsletter_heading.*' => 'required|string|max:100',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Footer Section' => 'assets/themes/deep_blue/img/section/footer.png',
            ]
        ],

        'login' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/deep_blue/img/section/login.png',
            ]
        ],

        'register' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Register Section' => 'assets/themes/deep_blue/img/section/register.png',
            ]
        ],

        'forgot_password' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/deep_blue/img/section/login.png',
            ]
        ],

        'how_to_mass_orders' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'description.*' => 'required|string|max:10000',
                ]
            ],
            'image' => [
                'How To Mass Order Section' => 'assets/themes/deep_blue/img/section/how_to_mass_orders.png',
            ]
        ],

        'child_panel_order_notes' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Child Panel Order Note Section' => 'assets/themes/deep_blue/img/section/child_panel_orders.png',
            ]
        ],
        'pwa_popup' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'domain_name' => 'text',
                    'short_description' => 'text',
                    'description' => 'text',
                    'icon_image' => 'file'
                ],
                'validation' => [
                    'title' => 'nullable',
                    'domain_name.*' => 'nullable',
                    'short_description.*' => 'nullable',
                    'description' => 'nullable',
                    'icon_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'carousel_image' => 'file'
                ],
                'validation' => [
                    'carousel_image.*' => 'required|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/images/preview/pwa.png'
        ],
    ],

    'dark_mode' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '450x526',
                ]
            ],
            'image' => [
                'Hero Section' => 'assets/themes/dark_mode/img/section/hero.png',
            ],
        ],

        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '450x421',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '24x24'
                ]
            ],
            'image' => [
                'About Section' => 'assets/themes/dark_mode/img/section/about.png',
            ],
        ],

        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'short_description.*' => 'required|string|min:1|max:500',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '84x80'
                ]
            ],
            'image' => [
                'Feature Section' => 'assets/themes/dark_mode/img/section/feature.png',
            ],
        ],

        'service' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'short_title.*' => 'required|max:2000'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '48x48',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/dark_mode/img/section/service.png',
            ]
        ],

        'how_it_works' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:2000',
                    'short_description.*' => 'required|max:2000',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'icon' => 'icon',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'icon.*' => 'required|max:100',
                    'short_description.*' => 'required|max:5000',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/dark_mode/img/section/how_it_works.png',
            ]
        ],

        'counter' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'number_of_data' => 'text',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'number_of_data.*' => 'required|integer',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '50x50'
                ]
            ],
            'image' => [
                'Why Choose Us Section' => 'assets/themes/dark_mode/img/section/counter.png',
            ]
        ],

        'call_to_action' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '386x386'
                ]
            ],
            'image' => [
                'How It Works Section' => 'assets/themes/dark_mode/img/section/call_to_action.png',
            ]
        ],

        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:400',
                    'short_description.*' => 'required|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'designation' => 'text',
                    'description' => 'textarea',
                    'image' => 'file'
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'designation.*' => 'required|max:2000',
                    'description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ]
            ],
            'image' => [
                'Testimonial Section' => 'assets/themes/dark_mode/img/section/testimonial.png',
            ]
        ],

        'payment_partner' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'image' => 'file',
                ],
                'validation' => [
                    'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '145x120',
                ]
            ],
            'image' => [
                'Payment partner Section' => 'assets/themes/dark_mode/img/section/payment_partner.png',
            ]
        ],

        'faq' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/dark_mode/img/section/faq.png',
            ]
        ],

        'blog' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Blog Section' => 'assets/themes/dark_mode/img/section/blog.png',
            ]
        ],

        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'title' => 'text',
                    'sub_title' => 'text',
                    'address' => 'text',
                    'email' => 'text',
                    'phone' => 'text',
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'address.*' => 'required|max:2000',
                    'email.*' => 'required|max:2000',
                    'phone.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Contact Section' => 'assets/themes/dark_mode/img/section/contact.png',
            ]
        ],

        'footer' => [
            'single' => [
                'field_name' => [
                    'site_short_description' => 'text',
                    'newsletter_heading' => 'text',
                ],
                'validation' => [
                    'site_short_description.*' => 'required|string|max:300',
                    'newsletter_heading.*' => 'required|string|max:100',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Footer Section' => 'assets/themes/dark_mode/img/section/footer.png',
            ]
        ],

        'login' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/dark_mode/img/section/login.png',
            ]
        ],

        'register' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Register Section' => 'assets/themes/dark_mode/img/section/register.png',
            ]
        ],

        'forgot_password' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/dark_mode/img/section/forgot_password.png',
            ]
        ],

        'how_to_mass_orders' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'description.*' => 'required|string|max:10000',
                ]
            ],
            'image' => [
                'How To Mass Order Section' => 'assets/themes/dark_mode/img/section/how_to_mass_orders.png',
            ]
        ],

        'child_panel_order_notes' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Child Panel Order Note Section' => 'assets/themes/dark_mode/img/section/child_panel_orders.png',
            ]
        ],

        'pwa_popup' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'domain_name' => 'text',
                    'short_description' => 'text',
                    'description' => 'text',
                    'icon_image' => 'file'
                ],
                'validation' => [
                    'title' => 'nullable',
                    'domain_name.*' => 'nullable',
                    'short_description.*' => 'nullable',
                    'description' => 'nullable',
                    'icon_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'carousel_image' => 'file'
                ],
                'validation' => [
                    'carousel_image.*' => 'required|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/images/preview/pwa.png'
        ],
    ],

    'light_orange' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '450x526',
                ]
            ],
            'image' => [
                'Hero Section' => 'assets/themes/light_orange/img/section/hero.png',
            ],
        ],

        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'youtube_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:100',
                    'button_link.*' => 'required|url',
                    'youtube_link.*' => 'required|url',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '450x421',
                ]
            ],
            'image' => [
                'About Section' => 'assets/themes/light_orange/img/section/about.png',
            ],
        ],

        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|string|min:1|max:100',
                    'short_description.*' => 'required|string|min:1|max:500',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '84x80'
                ]
            ],
            'image' => [
                'Feature Section' => 'assets/themes/light_orange/img/section/feature.png',
            ],
        ],

        'service' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'short_title.*' => 'required|max:2000'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'short_description.*' => 'required|string|max:2000',
                    'button_name.*' => 'required|string|max:200',
                    'button_link.*' => 'required|url|min:1|max:2000',
                    'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '48x48',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/light_orange/img/section/service.png',
            ]
        ],

        'how_it_works' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:2000',
                    'short_description.*' => 'required|max:2000',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'icon' => 'icon',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'icon.*' => 'required|max:100',
                    'short_description.*' => 'required|max:5000',
                ]
            ],
            'image' => [
                'Service Section' => 'assets/themes/light_orange/img/section/how_it_works.png',
            ]
        ],

        'counter' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'number_of_data' => 'text',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'number_of_data.*' => 'required|integer',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '50x50'
                ]
            ],
            'image' => [
                'Why Choose Us Section' => 'assets/themes/light_orange/img/section/counter.png',
            ]
        ],

        'call_to_action' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'button_name' => 'text',
                    'button_link' => 'url',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'button_link.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '386x386'
                ]
            ],
            'image' => [
                'How It Works Section' => 'assets/themes/light_orange//img/section/call_to_action.png',
            ]
        ],

        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:400',
                    'short_description.*' => 'required|max:2000',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'designation' => 'text',
                    'description' => 'textarea',
                    'image' => 'file'
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'designation.*' => 'required|max:2000',
                    'description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ]
            ],
            'image' => [
                'Testimonial Section' => 'assets/themes/light_orange/img/section/testimonial.png',
            ]
        ],

        'payment_partner' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|string|max:50',
                    'sub_title.*' => 'required|max:200',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'image' => 'file',
                ],
                'validation' => [
                    'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '145x120',
                ]
            ],
            'image' => [
                'Payment partner Section' => 'assets/themes/light_orange/img/section/payment_partner.png',
            ]
        ],

        'news_letter' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/light_orange/img/section/news_letter.png',
            ]
        ],

        'faq' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:191',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Faq Section' => 'assets/themes/light_orange/img/section/faq.png',
            ]
        ],

        'blog' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_title.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Blog Section' => 'assets/themes/light_orange/img/section/blog.png',
            ]
        ],

        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'title' => 'text',
                    'sub_title' => 'text',
                    'address' => 'text',
                    'email' => 'text',
                    'phone' => 'text',
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'address.*' => 'required|max:2000',
                    'email.*' => 'required|max:2000',
                    'phone.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Contact Section' => 'assets/themes/light_orange/img/section/contact.png',
            ]
        ],

        'footer' => [
            'single' => [
                'field_name' => [
                    'site_short_description' => 'text',
                ],
                'validation' => [
                    'site_short_description.*' => 'required|string|max:300',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'icon' => 'icon',
                    'link' => 'url',
                ],
                'validation' => [
                    'name.*' => 'required|string|max:100',
                    'icon.*' => 'required|string|max:100',
                    'link.*' => 'required|url|max:2000',
                ]
            ],
            'image' => [
                'Footer Section' => 'assets/themes/light_orange/img/section/footer.png',
            ]
        ],

        'login' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/light_orange/img/section/login.png',
            ]
        ],

        'register' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Register Section' => 'assets/themes/light_orange/img/section/register.png',
            ]
        ],

        'forgot_password' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required|max:2000'
                ]
            ],
            'image' => [
                'Login Section' => 'assets/themes/light_orange/img/section/forgot_password.png',
            ]
        ],

        'how_to_mass_orders' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|string|max:50',
                    'description.*' => 'required|string|max:10000',
                ]
            ],
            'image' => [
                'How To Mass Order Section' => 'assets/themes/light_orange/img/section/how_to_mass_orders.png',
            ]
        ],

        'child_panel_order_notes' => [
            'single' => [
                'field_name' => [
                    'short_description' => 'textarea',
                ],
                'validation' => [
                    'short_description.*' => 'required|string|max:2000',
                ]
            ],
            'image' => [
                'Child Panel Order Note Section' => 'assets/themes/light_orange/img/section/child_panel_orders.png',
            ]
        ],
        'pwa_popup' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'domain_name' => 'text',
                    'short_description' => 'text',
                    'description' => 'text',
                    'icon_image' => 'file'
                ],
                'validation' => [
                    'title' => 'nullable',
                    'domain_name.*' => 'nullable',
                    'short_description.*' => 'nullable',
                    'description' => 'nullable',
                    'icon_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'carousel_image' => 'file'
                ],
                'validation' => [
                    'carousel_image.*' => 'required|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/images/preview/pwa.png'
        ],
    ],


    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'icon' => 'icon',
        'count' => 'number',
        'button_link' => 'url',
        'youtube_link' => 'url',
        'video_link' => 'url',
        'background_image' => 'file',
        'image_one' => 'file',
        'image_two' => 'file',
        'map_link' => 'url',
        'link' => 'url',
        'overlay_image' => 'file',
        'icon_image' => 'file',
        'carousel_image' => 'file',
    ]
];

