@extends('layouts.default')
@push('title', get_phrase('Home'))
@push('meta')@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/fonts/urbanist/font.css') }}">
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        a,
        button {
            font-family: 'urbanist-regular' !important;
        }

        .btn-danger-1,
        .btn-whitelight,
        .info {
            font-family: 'urbanist-regular' !important;
        }

        .subtitle-1,
        .subtitle-2,
        .subtitle-3,
        .subtitle-4,
        .subtitle-5 {
            font-family: 'urbanist-semi-bold' !important;
            font-weight: 500;
        }

        .title,
        .title-1,
        .title-2,
        .title-3,
        .title-4,
        .title-5 {
            font-family: 'urbanist-bold' !important;
            font-weight: 700;
        }
    </style>
@endpush
@section('content')

    <!-- Banner Area Start -->
    <section class="lms-banner-section4">
        <div class="container">
            <div class="row mb-100px">
                <div class="col-md-12">
                    @php
                        $banner_title = get_frontend_settings('banner_title');
                    @endphp
                    <div class="lms-banner-area-4">
                        <h1 class="title-5 max-w-765px fs-56px lh-normal fw-bold text-white text-center mb-20px">{{ $banner_title }}</h1>
                        <p class="subtitle-5 max-w-765px fs-15px lh-24px text-white text-center mb-30px">{{ get_frontend_settings('banner_sub_title') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area End -->

    <!-- Service Area Start -->
    <section>
        <div class="container">
            <div class="row g-28px mb-100px">
                @foreach (App\Models\Category::take(8)->get() as $category)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a class="w-100" href="{{ route('courses', $category->slug) }}">
                            <div class="lms-service-card-2 max-sm-350px">
                                <div class="service-card-banner-2 mb-20px">
                                    @if ($category->thumbnail)
                                        <img src="{{ get_image($category->thumbnail) }}" alt="">
                                    @endif
                                </div>
                                <div>
                                    <h4 class="title-5 fs-20px lh-28px fw-bold mb-2 text-center">{{ $category->title }}</h4>
                                    <p class="subtitle-5 fs-15px lh-25px text-center">{{ count_category_courses($category->id) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- Service Area End -->

    <!-- Creating A Community Area Start -->
    <section>
        <div class="container">
            <div class="row g-28px align-items-center mb-100px">
                <div class="col-xl-5 col-lg-6">
                    <div class="community-banner-2">
                        @php
                            $storImage = json_decode(get_homepage_settings('university'));
                        @endphp
                        @if (isset($storImage->faq_image))
                            <img src="{{ asset('uploads/home_page_image/university/' . $storImage->image) }}" alt="">
                        @endif
                    </div>
                </div>
                <div class="col-xl-7 col-lg-6">
                    <div class="ms-xl-3">
                        <h2 class="title-5 fs-32px lh-42px fw-bold mb-30px">{{ get_phrase('Creating A Community Of Life Long Learners') }}</h2>
                        <p class="subtitle-5 fs-15px lh-25px mb-30px">
                            {{ get_phrase("Our LMS goes beyond just providing courses. It's a platform designed to ignite curiosity and empower your lifelong learning journey.  This supportive community provides a space to ask questions, no matter how big or small, and receive insightful answers from experienced learners and subject-matter experts.") }}
                        </p>
                        <p class="subtitle-5 fs-15px lh-25px mb-30px">
                            {{ get_phrase("Share your own experiences and challenges, and find encouragement and inspiration from others on a similar path. The diverse perspectives within our community will broaden your horizons and challenge your thinking, fostering a deeper understanding and a richer learning experience.  Together, we'll transform learning from a solitary pursuit into a collaborative adventure, where shared knowledge fuels individual growth and collective discovery.")}}
                        </p>
                        <a href="{{ route('about.us') }}" class="btn btn-danger-1">{{ get_phrase('Learn more about us') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Creating A Community Area End -->
    <!-- Online Course Area Start -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-2 mb-50px">
                        <h1 class="title-5 fs-32px lh-42px fw-bold mb-20px text-center">{{ get_phrase('Our Online Courses') }}</h1>
                        <p class="subtitle-5 fs-15px lh-24px text-center">
                            {{ get_phrase('Awesome  site. on the top advertising a business online includes assembling Having the most keep.') }}</p>
                    </div>
                </div>
            </div>
            <div class="row g-28px mb-50px">
                @php
                    $featured_courses = DB::table('courses')->where('status', 'active')->latest('id')->take(4)->get();
                @endphp
                @foreach ($featured_courses->take(4) as $key => $row)
                    @php
                        $ratings = DB::table('reviews')
                            ->where('course_id', $row->id)
                            ->pluck('rating')
                            ->toArray();
                        $average_rating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : null;
                    @endphp
                    <div class="col-lg-6">
                        <a href="{{ route('course.details', $row->slug) }}" class="d-block h-100 w-100 max-sm-350px">
                            <div class="lms-1-card">
                                <div class="lms-1-card-body">
                                    <div class="d-flex gap-3 align-items-center flex-sm-row flex-column">
                                        <div class="list-view-banner2">
                                            <img src="{{ get_image($row->thumbnail) }}" alt="banner">
                                        </div>
                                        <div class="list-view-details1 w-100">
                                            <h5 class="title-5 fs-18px lh-26px fw-bold mb-12px">{{ ucfirst($row->title) }}</h5>
                                            <p class="subtitle-5 fs-14px lh-24px mb-10px">{{ Str::limit(strip_tags($row->short_description), 78) }}</p>
                                            <div class="card-leason-rating2 d-flex gap-2 align-items-center justify-content-between">
                                                <div class="card-icon-text4 d-flex gap-2 align-items-center">
                                                    <span class="fi-rr-book-open-cover"></span>
                                                    <p class="subtitle-5 fs-13px lh-26px">{{ lesson_count($row->id) }} {{ get_phrase('lesson') }}</p>
                                                </div>
                                                <div class="card-rating3 d-flex gap-1 align-items-center">
                                                    @if ($average_rating !== null)
                                                        <p class="title-5 fs-15px lh-26px fw-medium">{{ number_format($average_rating, 1) }}</p>
                                                        <img src="{{ asset('assets/frontend/default/image/star-yellow-14.svg') }}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <div class="d-flex align-items-center gap-12px">
                                                    <div class="card-author-sm">
                                                        <img src="{{ course_instructor_image($row->id) }}" alt="">
                                                    </div>
                                                    <div class="title-5 fs-13px lh-26px fw-medium">{{ course_by_instructor($row->id)->name }}</div>
                                                </div>
                                                @if (isset($row->discount_flag) && $row->discount_flag == 1)
                                                    <h4 class="title-5 fs-20px lh-26px fw-bold text-danger-2">{{ currency(number_format($row->price - $row->discounted_price, 2)) }}
                                                    </h4>
                                                @elseif (isset($row->is_paid) && $row->is_paid == 0)
                                                    <h4 class="title-5 fs-20px lh-26px fw-bold text-danger-2">{{ get_phrase('Free') }}</h4>
                                                @else
                                                    <h4 class="title-5 fs-20px lh-26px fw-bold text-danger-2">{{ currency(number_format($row->price, 2)) }}</h4>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="row mb-100px">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="{{ route('courses') }}" class="btn btn-danger-1">{{ get_phrase('See More') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Online Course Area End -->

    <!-- Event Area Start -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-2 mb-50px">
                        <h1 class="title-5 fs-32px lh-42px fw-bold mb-20px text-center">{{ get_phrase('Think more clearly') }}</h1>
                        <p class="subtitle-5 fs-15px lh-24px text-center">
                            {{ get_phrase('Awesome  site. on the top advertising a business online includes assembling Having the most keep.') }}</p>
                    </div>
                </div>
            </div>
            <div class="row mb-50px">
                <div class="col-12">
                    <div class="lms-event-wrap1">
                        @php
                            $motivational_speeches = json_decode(get_frontend_settings('motivational_speech'), true);
                            $increment = 1;
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="lms-event-single1 d-flex gap-2">
                                @php
                                    $admininfo = DB::table('users')->where('role', 'admin')->first();
                                @endphp
                                <div class="lms-event-number">
                                    <h1 class="title-5 fs-44px lh-29px fw-bold">{{ $increment++ }}</h1>
                                </div>
                                <div class="event-details-banner-wrap w-100 d-flex">
                                    <div>
                                        <h3 class="title-5 fs-20px lh-26px fw-bold mb-14px">{{ $motivational_speech['title'] }}</h3>
                                        <div class="d-flex align-items-center gap-12px mb-20px">
                                            <div class="lms-author-sm">
                                                @if ($admininfo->photo)
                                                    <img src="{{ get_image($admininfo->photo) }}" alt="">
                                                @else
                                                    <img src="{{ asset('uploads/users/admin/placeholder/placeholder.png') }}" alt="">
                                                @endif
                                            </div>
                                            <div class="title-5 fs-13px lh-26px fw-medium">{{ $admininfo->name }}</div>
                                        </div>
                                        <p class="subtitle-5 fs-15px lh-25px">{{ $motivational_speech['description'] }}</p>
                                    </div>
                                    <div class="lms-event1-banner">
                                        @if ($motivational_speech['image'])
                                        <img src="{{ get_image($motivational_speech['image']) }}" alt="">
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Event Area End -->

    <!-- Tuition Area Start -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-2 mb-50px">
                        <h1 class="title-5 fs-32px lh-42px fw-bold mb-20px text-center">{{ get_phrase('Frequently Asked Questions') }}</h1>
                        <p class="subtitle-5 fs-15px lh-24px text-center">
                            {{ get_phrase('Awesome  site. on the top advertising a business online includes assembling Having the most keep.') }}</p>
                    </div>
                </div>
            </div>
            <div class="row g-28px align-items-center mb-100px">
                <div class="col-lg-5">
                    <div class="tuition-banner">
                        @if (isset($storImage->faq_image))
                            <img src="{{ asset('uploads/home_page_image/university/' . $storImage->faq_image) }}" alt="">
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="lms-1-card rounded-20px">
                        <div class="lms-1-card-body p-40px">
                            <div class="accordion qnaaccordion-five" id="accordionExample5">
                                @php
                                    $faqs = count(json_decode(get_frontend_settings('website_faqs'), true)) > 0 ? json_decode(get_frontend_settings('website_faqs'), true) : [['question' => '', 'answer' => '']];
                                @endphp
                                @foreach ($faqs as $key => $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="{{ $key }}">
                                            <button class="accordion-button  {{ $key == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#qnaOne{{ $key }}" aria-expanded="true" aria-controls="qnaOne">
                                                {{ $faq['question'] }}
                                            </button>
                                        </h2>
                                        <div id="qnaOne{{ $key }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="{{ $key }}" data-bs-parent="#accordionExample5">
                                            <div class="accordion-body">
                                                <p class="answer">{{ $faq['answer'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Tuition Area End -->

    <!-- Testimonial Area End -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-2 mb-30px">
                        <h1 class="title-5 fs-32px lh-42px fw-bold text-center">{{ get_phrase('What the people Thinks About Us') }}</h1>
                    </div>
                </div>
            </div>
            <div class="row mb-50px">
                <div class="col-md-12">
                    <div class="swiper lms-testimonial-2">
                        <div class="swiper-wrapper">
                            @php
                                $reviews = DB::table('user_reviews')->get();
                            @endphp
                            @foreach ($reviews as $review)
                                @php
                                    $userDetails = DB::table('users')
                                        ->where('id', $review->user_id)
                                        ->first();
                                @endphp
                                <div class="swiper-slide">
                                    <div class="lms-1-card rounded-4">
                                        <div class="lms-single-testimonial2">
                                            <div class="d-flex justify-content-between gap-2 mb-14px">
                                                <div class="testimonial-profile-wrap2 d-flex align-items-center ">
                                                    <div class="testimonial-profile-2">
                                                        <img src="{{ get_image_by_id($review->id) }}" alt="">
                                                    </div>
                                                    <div>
                                                        <h4 class="title-5 fs-18px lh-25px fw-semibold mb-5px">{{ $userDetails->name }}</h4>
                                                        <p class="subtitle-5 fs-14px lh-24px">{{ date('h:i A', strtotime($review->created_at)) }}</p>
                                                    </div>
                                                </div>
                                                <div class="testimonial-quate-1">
                                                    <img src="assets/images/icons/quate-red.svg" alt="">
                                                </div>
                                            </div>
                                            <p class="subtitle-5 fs-15px lh-24px mb-14px">{{ $review->review }}</p>
                                            <div class="d-flex align-items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <img src="{{ asset('assets/frontend/default/image/star-yellow-14.svg') }}" alt="">
                                                    @else
                                                        <img src="{{ asset('assets/frontend/default/image/star.svg') }}" alt="">
                                                    @endif
                                                @endfor
                                                <h6 class="title-5 ms-2 fs-18px lh-37px fw-semibold">({{ $review->rating }})</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Area End -->


    <!-- Blog Area Start -->
    @if (get_frontend_settings('blog_visibility_on_the_home_page'))
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title-1 mb-50px">
                            <h1 class="title-3 mb-26px fs-40px lh-52px fw-medium text-center">{{ get_phrase('Our Blog') }}</h1>
                            <p class="subtitle-2 fs-15px lh-24px text-center">
                                {{ get_phrase('Awesome  site. on the top advertising a business online includes assembling Having the most keep.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row g-20px mb-100px">
                    @foreach ($blogs as $key => $blog)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <a href="{{ route('blog.details', $blog->slug) }}" class="blog-post1-link">
                                <div class="blog-post1-inner">
                                    <div class="banner">
                                        <img src="{{ get_image($blog->thumbnail) }}" alt="...">
                                    </div>
                                    <div class="blog-post1-details">
                                        <h3 class="title-5 mb-3 pt-2">{{ ucfirst($blog->title) }}</h3>
                                        <p class="info ellipsis-line-3">{{ellipsis(strip_tags($blog->description), 160) }}</p>
                                        <p class="read-more d-flex align-items-center">
                                            <span>{{ get_phrase('Read More') }}</span>
                                            <img src="{{ asset('assets/frontend/default/image/angle-right-black-18.svg') }}" alt="">
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Blog Area End -->

@endsection
