<div class="row">
    @forelse ($properties as $property)
        <div class="col-sm-6 col-xl-3">
            <div class="listing-style1">
                <div class="list-thumb">

                    @if (!empty($property->thumbnail) && !empty($property->thumbnail->image))
                        @php $thumbnail= $property->thumbnail->image; @endphp
                    @else
                        @php $thumbnail= 'default.jpg'; @endphp
                    @endif


                    <a href="{{ route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)]) }}">
                        <img class="location-img"
                            src="{{ asset(Storage::url('upload/property/thumbnail/' . $thumbnail)) }}" alt="image">
                    </a>
                </div>


                <div class="list-content">
                    <p class="list-text body-color fz16 mb-1"><span class="badge bg-light-secondary">
                            {{ \App\Models\Property::$Type[$property->type] }}</span></p>
                    <h5 class="list-title"><a
                            href="{{ route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)]) }}">{{ ucfirst($property->name) }}</a>
                    </h5>
                    <p class="mb-0 body-color">
                        <span class="fz12 ms-1">
                            {{ \Illuminate\Support\Str::limit(strip_tags($property->description), 50, '...') }}
                        </span>
                    </p>
                    <hr class="my-2">
                    <div
                        class="list-meta d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-3 gap-3">

                        <div class="w-100 w-md-50">
                            <p class="fz14 mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $property->address }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-center">{{ $noPropertiesMessage }}</p>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="mbp_pagination text-center">
        @if ($properties->hasPages())
            <ul class="page_navigation">
                @if ($properties->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><span
                                class="fas fa-angle-left"></span></span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $properties->previousPageUrl() }}"><span
                                class="fas fa-angle-left"></span></a></li>
                @endif

                @foreach ($properties->links()->elements[0] as $page => $url)
                    @if (is_string($page))
                        <li class="page-item disabled"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item {{ $page == $properties->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                @if ($properties->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $properties->nextPageUrl() }}"><span
                                class="fas fa-angle-right"></span></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><span
                                class="fas fa-angle-right"></span></span></li>
                @endif
            </ul>
        @endif

        <p class="mt10 mb-0 pagination_page_count text-center">
            {{ ($properties->currentPage() - 1) * $properties->perPage() + 1 }} –
            {{ min($properties->currentPage() * $properties->perPage(), $properties->total()) }}
            of {{ $properties->total() }} property available
        </p>
    </div>
</div>
