<div>
    <div>
        <main class="panel-main">
            @livewire('panel.header')

            <section class="invoice-section">
                <div class="panel-card">


                    <div class="table-responsive">
                        <table class="besmani-table business-list-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $row = 1; @endphp
                                @foreach (BusinessList() as $activity)
                                    @foreach ($activity->infoActivity ?? [] as $business)
                                        @php
                                            $img = $business->image ?? '';
                                            $beautyUrl = rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/');
                                            $activityImagesBase = $beautyUrl . '/public/assets/images/activity_images/';
                                            if ($img) {
                                                $imgUrl = str_starts_with($img, 'http') ? $img : (str_contains($img, 'activity_images') ? $beautyUrl . '/' . ltrim($img, '/') : $activityImagesBase . basename($img));
                                            } else {
                                                $imgUrl = '';
                                            }
                                            $name = $business->name ?? $business->title ?? '-';
                                            $nameShort = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;
                                        @endphp
                                        <tr>
                                            <td class="business-list-activity-number" style="width: 50px;">{{ $row++ }}</td> 
                                            <td>
                                                <div class="business-list-activity-cell">
                                                    <div class="business-list-activity-img-wrap">
                                                        @if($imgUrl)
                                                            <img src="{{ $imgUrl }}" alt="{{ $name }}" class="business-list-activity-img">
                                                        @else
                                                            <div class="business-list-activity-img business-list-activity-img-placeholder">
                                                                <i class="fa fa-building"></i>
                                                            </div>
                                                        @endif 
                                                    </div>
                                                    <div class="business-list-activity-text">
                                                        <span class="business-list-activity-title">{{ $activity->title ?? $activity->title_en ?? 'Activity' }} :</span>
                                                        <span class="business-list-activity-name">{{ $nameShort }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </section>
        </main>
    </div>

</div>
