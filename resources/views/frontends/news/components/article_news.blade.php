@php
    $isDetail = $isDetail ?? false;
    // Determine Post URL (Slug preferred)
    $postUrl = route('news.show', $post->post_name ?? $post->slug ?? $post->id);

    // Determine Image URL
    if (!isset($thumbUrl)) {
        $thumbUrl = asset('images/all/blog/1.jpg'); // Default
        $thumbMeta = $post->meta->where('meta_key', '_thumbnail')->first();
        if ($thumbMeta && $thumbMeta->meta_value) {
            if (file_exists(public_path('assets/images/posts/' . basename($thumbMeta->meta_value)))) {
                $thumbUrl = asset('assets/images/posts/' . basename($thumbMeta->meta_value));
            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($thumbMeta->meta_value)) {
                $thumbUrl = \Illuminate\Support\Facades\Storage::url($thumbMeta->meta_value);
            }
        }
    }

    // Author
    $authorName = $post->author->name ?? 'Admin';
    $authorAvatar = optional($post->author)->avatar ? asset(optional($post->author)->avatar) : asset('images/avatar/1.jpg');

    // Date
    // HuyTBQ: Format date to d/m/Y (Vietnamese standard) and add fallback to post_date if created_at is null
    $dateObj = $post->created_at ?? $post->post_date;
    $date = $dateObj ? \Carbon\Carbon::parse($dateObj)->format('d/m/Y') : '';

    // Views
    $views = $post->view_count ?? $post->views ?? $post->comment_count ?? 0;
@endphp

<article class="post-article fl-wrap">
    <div class="list-single-main-media fl-wrap">
        @if($isDetail && method_exists($post, 'gallery') && $post->gallery && count($post->gallery))
            <div class="single-slider-wrapper carousel-wrap fl-wrap">
                <div class="single-slider fl-wrap carousel lightgallery">
                    <!-- Featured image -->
                    <div class="slick-slide-item">
                        <div class="box-item">
                            <a href="{{ $thumbUrl }}" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                            <img src="{{ $thumbUrl }}" alt="{{ $post->post_title }}">
                        </div>
                    </div>
                    <!-- Gallery images -->
                    @foreach($post->gallery as $g)
                        @php
                            $gUrl = (file_exists(public_path('assets/images/posts/' . basename($g))) ? asset('assets/images/posts/' . basename($g)) : (\Illuminate\Support\Facades\Storage::disk('public')->exists($g) ? \Illuminate\Support\Facades\Storage::url($g) : $thumbUrl));
                        @endphp
                        <div class="slick-slide-item">
                            <div class="box-item">
                                <a href="{{ $gUrl }}" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                                <img src="{{ $gUrl }}" alt="{{ $post->post_title }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev ssw-btn"><i class="fas fa-caret-left"></i></div>
                <div class="swiper-button-next ssw-btn"><i class="fas fa-caret-right"></i></div>
            </div>
        @else
            <!-- Simplified image display for listing or no gallery -->
            <div class="single-slider-wrapper fl-wrap">
                <div class="box-item">
                    <a href="{{ $thumbUrl }}" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                    <img src="{{ $thumbUrl }}" alt="{{ $post->post_title }}" class="resp-img">
                </div>
            </div>
        @endif
    </div>
    <div class="list-single-main-item fl-wrap block_box">
        @if($isDetail)
            <div class="single-article-header fl-wrap">
                <h2 class="post-opt-title"><a href="#">{{ $post->post_title }}</a></h2>
                <span class="fw-separator"></span>
                <div class="clearfix"></div>
        @else
            <h2 class="post-opt-title"><a href="{{ $postUrl }}">{{ $post->post_title }}</a></h2>
            <p>{{ $post->post_excerpt }}</p>
            <span class="fw-separator fl-wrap"></span>
        @endif

        {{-- <div class="post-author">
            <a href="#"><img src="{{ $authorAvatar }}" alt="{{ $authorName }}"><span>By , {{ $authorName }}</span></a>
        </div> --}}
        <div class="post-opt">
            <ul class="no-list-style">
                <li><i class="fal fa-calendar"></i> <span>{{ $date }}</span></li>
                <li><i class="fal fa-eye"></i> <span>{{ $views }}</span></li>
                <li><i class="fal fa-tags"></i>
                    @if(isset($post->tags) && count($post->tags))
                        @foreach($post->tags as $t)
                            @php
                                $slug = $t->slug ?? data_get($t, 'term.slug') ?? data_get($t, 'term_id') ?? data_get($t, 'id');
                                $label = $t->name ?? data_get($t, 'term.name') ?? $t->tag ?? ($slug ?? 'Tag');
                                $tagUrl = $slug ? (function_exists('route') && \Route::has('news.tag') ? route('news.tag', $slug) : url('/tin-tuc/tag/' . $slug)) : '#';
                            @endphp
                            <a href="{{ $tagUrl }}">{{ $label }}</a>@if(!$loop->last), @endif
                        @endforeach
                    @elseif(isset($post->meta))
                         @php
                            $tagMeta = $post->meta->where('meta_key','_tags')->first();
                            $tagNames = $tagMeta ? explode(',', $tagMeta->meta_value) : [];
                        @endphp
                        @foreach($tagNames as $tn)
                            <a href="#">{{ trim($tn) }}</a>@if(!$loop->last), @endif
                        @endforeach
                    @endif
                </li>
            </ul>
        </div>

        @if($isDetail)
            </div> <!-- End single-article-header -->
            <span class="fw-separator fl-wrap"></span>
            {!! $post->post_content !!}
            <span class="fw-separator fl-wrap"></span>
            <div class="list-single-tags tags-stylwrap">
                <span class="tags-title">Tags:</span>
                @if(isset($post->tags) && $post->tags->count() > 0)
                    @foreach($post->tags as $tag)
                        @if(isset($tag->term))
                            <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>
                        @endif
                    @endforeach
                @else
                    <span>Không có tags</span>
                @endif
            </div>
        @else
            <a href="{{ $postUrl }}" class="btn color-bg float-btn small-btn">Xem thêm</a>
        @endif
    </div>
</article>
