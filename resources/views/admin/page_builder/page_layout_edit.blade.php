<!DOCTYPE html>
<html lang="en">

<head>
    {{ config(['app.name' => get_settings('system_title')]) }}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ get_phrase('Edit Layout') }} | {{ config('app.name') }}</title>
    <meta content="{{ csrf_token() }}" name="csrf_token" />

    <link rel="shortcut icon" href="{{ asset(get_frontend_settings('favicon')) }}" />

    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/owl.theme.default.min.css') }}">

    <!-- Jquery Ui Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/jquery-ui.css') }}">

    <!-- Nice Select Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/nice-select.css') }}">

    <!-- Fontawasome Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/all.min.css') }}">

    <!-- FlatIcons Css -->
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-bold-rounded/css/uicons-bold-rounded.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-bold-straight/css/uicons-bold-straight.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-regular-rounded/css/uicons-regular-rounded.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-solid-rounded/css/uicons-solid-rounded.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-solid-rounded/css/uicons-solid-rounded.css') }}" />

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/custome-front/custom-fronts.css') }}">

    <!-- Player Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/plyr.css') }}">

    <!-- Player Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/sweet_alert.css') }}">

    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/bootstrap.min.css') }}">

    <!-- Main Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/custom_style.css') }}">



    <!-- Jquery Js -->
    <script src="{{ asset('assets/frontend/default/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap Js -->
    <script src="{{ asset('assets/frontend/default/js/bootstrap.bundle.min.js') }}"></script>

    <!-- nice select js -->
    <script src="{{ asset('assets/frontend/default/js/jquery.nice-select.min.js') }}"></script>

    <!-- owl carousel js -->
    <script src="{{ asset('assets/frontend/default/js/owl.carousel.min.js') }}"></script>

    <!-- Player Js -->
    <script src="{{ asset('assets/frontend/default/js/plyr.js') }}"></script>

    <!-- Jquery Ui Js -->
    <script src="{{ asset('assets/frontend/default/js/jquery-ui.min.js') }}"></script>

    <!-- price range Js -->
    <script src="{{ asset('assets/frontend/default/js/price_range_script.js') }}"></script>

    <!-- Main Js -->
    <script src="{{ asset('assets/frontend/default/js/script.js') }}"></script>
    <!-- toster file -->
    @include('frontend.default.toaster')

    <style>
        .text-30px {
            font-size: 30px;
        }
    </style>
</head>

<body>

    <!-- Builder bar -->
    <div id="editor_top_bar" class="editor_top_bar">
        <div class="container">
            <div class="row">
                <div class="col py-3">
                    <div class="editor_title">{{ get_phrase('Page Builder') }}</div>
                </div>
                <div class="col py-2">
                    <a class="btn btn-dark btn-lg float-end mx-1" href="{{ route('admin.pages') }}">
                        <i class="fi-rr-angle-left"> </i>{{ get_phrase('Back') }}</a>
                    <button class="btn btn-dark btn-lg float-end mx-1 save-button" onclick="save_layout()">
                        <i class="fi-rr-arrow-alt-square-down"> </i>{{ get_phrase('Save Changes') }}
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div id="main">
        @php $builder_file_names = App\Models\Builder_page::where('id', $id)->firstOrNew()->html; @endphp
        @foreach (json_decode($builder_file_names, true) ?? [] as $key => $builder_file_name)
            <div builder-block-identifier="{{time().rand(1000, 9999).$key}}" builder-block-file-name="{{$builder_file_name}}">
                @include('components.home_made_by_builder.'.$builder_file_name)
            </div>
        @endforeach
    </div>

    


    @include ('admin.page_builder.page_layout_edit_offcanvas')
    @include ('admin.toaster')
    <script>
        "use strict";

        // Save the edited layout into database
        function save_layout() {

            // Remove builder tool START
            // removes the options elements: buttons & borders
            $(".content_editor_buttons").remove();
            $('.builder-editable.initialized').removeClass('initialized');
            $('#main .builder-editable-wraper > .builder-editable').each(function(index, elem) {
                //To remove parent div (.builder-editable-wraper)
                $(this).unwrap();
            });
            // Remove builder tool END

            // now extract the plain html
            let output = $("#main").html();

            // Save the modified html codes to server
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Sending the ajax call
            let url = "{{ route('admin.page.layout.update', ['id' => $id]) }}";
            $.ajax({
                type: "POST",
                data: {
                    html: output,
                    id: {{ $id }}
                },
                url: url,
                success: function(msg) {
                    success('Layout updated');
                    // Re-initiate the builder
                    builder_initiated()
                }
            });

            
        }
    </script>



    <style>
        .parent {
            position: relative;
            /* Add any other styles you want for the parent div */
        }

        .parent:hover {
            outline: 2px dashed black;
            display: block;
        }

        .child {
            position: absolute;
            display: none;
            float: right;
            text-align: center;
            top: 0;
            left: 0;
            width: 100%;
            cursor: move;
            margin-top: 5px;
        }

        .parent:hover>.child {
            display: block;
        }

        .block_delete {
            border-radius: 5px 0px 0px 5px !important;
            margin-right: -5px !important;
        }

        .block_add {
            border-radius: 0px 5px 5px 0px !important;
        }


        .editor_top_bar {
            background-color: #121729;
        }

        .editor_title {
            color: #faf4ff;
            font-size: 14px;
        }

        .builder_image img {
            display: block;
        }

        .placeholder_block {
            text-align: center;
            outline: 2px dashed #c1b4d8;
            padding: 50px;
            margin-top: 50px;
            border-radius: 10px;
        }

        .placeholder_block>div {
            margin-top: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            color: #12172a;
        }

        .toast {
            font-size: 13px;
        }

        /* Flaticon spacing isse fixed START*/
        i:not(.fas, .fa, .fab) {
            line-height: 1.5 !important;
            vertical-align: -0.12em !important;
            display: inline-flex !important;
            margin: 3px;
        }

        /* Flaticon spacing isse fixed END*/
    </style>

</body>

</html>
