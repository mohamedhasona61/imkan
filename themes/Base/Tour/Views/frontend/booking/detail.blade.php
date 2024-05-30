@php $lang_local = app()->getLocale() @endphp
<div class="booking-review">
    <h4 class="booking-review-title">{{ __('Your Booking') }}</h4>
    <div class="booking-review-content">
        <div class="review-section">
            <div class="service-info">
                <div>
                    @php
                        $service_translation = $service->translate($lang_local);
                    @endphp
                    <h3 class="service-name"><a href="{{ $service->getDetailUrl() }}">{!! clean($service_translation->title) !!}</a></h3>
                    @if ($service_translation->address)
                        <p class="address"><i class="fa fa-map-marker"></i>
                            {{ $service_translation->address }}
                        </p>
                    @endif
                </div>
                @php $vendor = $service->author; @endphp
                @if ($vendor->hasPermission('dashboard_vendor_access') and !$vendor->hasPermission('dashboard_access'))
                    <div class="mt-1">
                        <i class="icofont-info-circle"></i>
                        {{ __('Vendor') }}: <a href="{{ route('user.profile', ['id' => $vendor->id]) }}"
                            target="_blank">{{ $vendor->getDisplayName() }}</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="review-section">
            <ul class="review-list">
                @if ($booking->start_date)
                    <li>
                        <div class="label">{{ __('Start date:') }}</div>
                        <div class="val">
                            {{ display_date($booking->start_date) }}
                        </div>
                    </li>
                    <li>
                        <div class="label">{{ __('Duration:') }}</div>
                        <div class="val">
                            {{ human_time_diff($booking->end_date, $booking->start_date) }}
                        </div>
                    </li>
                    @php
                    $tour_price = \Modules\Tour\Models\Tour::where('id', $booking->object_id)->first(); @endphp
                    @if ($tour_price->price > 0)
                        <li>
                            <div class="label"> {{ __('Main Price') }}:</div>
                            <div class="val">
                                {{ $tour_price->price }}
                            </div>
                        </li>
                    @endif
                @endif
                @php $person_types = $booking->getJsonMeta('person_types')@endphp

                @if (!empty($person_types))
                    @foreach ($person_types as $type)
                        <li>
                            <div class="label">{{ $type['name_' . $lang_local] ?? __($type['name']) }}:</div>
                            <div class="val">
                                {{ $type['number'] }}
                            </div>
                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="label">{{ __('Guests') }}:</div>
                        <div class="val">
                            {{ $booking->total_guests }}
                        </div>
                    </li>
                @endif

            </ul>
        </div>
        @do_action('booking.checkout.before_total_review')
        <div class="review-section total-review">
            <ul class="review-list">
                @php $person_types = $booking->getJsonMeta('person_types') @endphp
                @if (!empty($person_types))
                    @foreach ($person_types as $type)
                        <li>



                            <div class="label">
                                @if (isset($type['name_' . $lang_local]))
                                    {{ $type['name_' . $lang_local] }}
                                @else
                                    {{ __($type['name']) }}
                                @endif
                                : {{ $type['number'] }} *
                                @if (isset($type['display_price']))
                                    {{ format_money($type['display_price']) }}
                                @else
                                    {{ format_money($type['price']) }}
                                @endif
                            </div>

                            @if (isset($type['display_price']))
                                <div class="val">
                                    {{ format_money($type['display_price'] * $type['number']) }}
                                </div>
                            @else
                                <div class="val">
                                    {{ format_money($type['price']) }}
                                </div>
                            @endif

                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="label">{{ __('Guests') }}: {{ $booking->total_guests }} *
                            {{ format_money($booking->getMeta('base_price')) }}</div>
                        <div class="val">
                            {{ format_money($booking->getMeta('base_price') * $booking->total_guests) }}
                        </div>
                    </li>
                @endif

                @if ($booking->is_app == 0)
                    @php
                        $extra_price = $booking->getJsonMeta('extra_price');
                        $extra_price = json_decode($extra_price, true);
                    @endphp
                    @foreach ($extra_price as $extra_price)
                        @php
                            $extra_price = json_encode($extra_price);
                            $extra_price = json_decode($extra_price);
                        @endphp
                        @if (!empty($extra_price))
                            <li>
                                <div class="label-title"><strong>{{ __('Extra Prices:') }}</strong></div>
                            </li>
                            <li class="no-flex">
                                <ul>
                                    @foreach ($extra_price as $type)
                                        <li>
                                            <div class="label">{{ $extra_price->name ?? '' }}::</div>
                                            <div class="val">
                                                {{ $extra_price->price }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                @endif
                @if ($booking->is_app == 1)
                    @php
                        $extra_price = $booking->getJsonMeta('extra_price');
                    @endphp
                    @if (!empty($extra_price) && count($extra_price) > 0)
                                                <li>
                                <div class="label-title"><strong>{{ __('Extra Prices:') }}</strong></div>
                            </li>

                        @foreach ($extra_price as $extra_price)
                            <li class="no-flex">
                                <ul>
                                        <li>
                                            <div class="label">{{ $extra_price['name'] }}</div>
                                            <div class="val">
                                                {{ $extra_price['price'] }}
                                            </div>
                                        </li>
                                </ul>
                            </li>
                        @endforeach
                    @endif
                @endif
                @if (!empty($selectedItems))
                    <li>
                        <div class="label-title"><strong>{{ __('Menus:') }}</strong></div>
                    </li>
                    <li class="no-flex">
                        <ul>
                            @foreach ($selectedItems as $type)
                                <li>
                                    @if (app()->getLocale() == 'ar')
                                        <div class="label">{{ $type->name }}:</div>
                                    @else
                                        <div class="label">{{ $type->name_en }}:</div>
                                    @endif
                                    <div class="val">
                                        @if ($type->price > 0)
                                            {{ $type->count }} * {{ $type->price }}
                                        @else
                                            {{ $type->count }} ({{ __('Included') }})
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @php $discount_by_people = $booking->getJsonMeta('discount_by_people')@endphp
                @if (!empty($discount_by_people))
                    <li>
                        <div class="label-title"><strong>{{ __('Discounts:') }}</strong></div>
                    </li>
                    <li class="no-flex">
                        <ul>
                            @foreach ($discount_by_people as $type)
                                <li>
                                    <div class="label">
                                        @if (!$type['to'])
                                            {{ __('from :from guests', ['from' => $type['from']]) }}
                                        @else
                                            {{ __(':from - :to guests', ['from' => $type['from'], 'to' => $type['to']]) }}
                                        @endif
                                        :
                                    </div>
                                    <div class="val">
                                        - {{ format_money($type['total'] ?? 0) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @php
                    $list_all_fee = [];
                    if (!empty($booking->buyer_fees)) {
                        $buyer_fees = json_decode($booking->buyer_fees, true);
                        $list_all_fee = $buyer_fees;
                    }
                    if (!empty(($vendor_service_fee = $booking->vendor_service_fee))) {
                        $list_all_fee = array_merge($list_all_fee, $vendor_service_fee);
                    }
                @endphp
                @if (!empty($list_all_fee))
                    @foreach ($list_all_fee as $item)
                        @php
                            $fee_price = $item['price'];
                            if (!empty($item['unit']) and $item['unit'] == 'percent') {
                                $fee_price = ($booking->total_before_fees / 100) * $item['price'];
                            }
                        @endphp
                        <li>
                            <div class="label">
                                {{ $item['name_' . $lang_local] ?? $item['name'] }}
                                <i class="icofont-info-circle" data-toggle="tooltip" data-placement="top"
                                    title="{{ $item['desc_' . $lang_local] ?? $item['desc'] }}"></i>
                                @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                    : {{ $booking->total_guests }} * {{ format_money($fee_price) }}
                                @endif
                            </div>
                            <div class="val">
                                @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                    {{ format_money($fee_price * $booking->total_guests) }}
                                @else
                                    {{ format_money($fee_price) }}
                                @endif
                            </div>
                        </li>
                    @endforeach
                @endif
                @includeIf('Coupon::frontend/booking/checkout-coupon')
                <li class="final-total d-block">
                    <div class="d-flex justify-content-between">
                        <div class="label">{{ __('Total:') }}</div>
                        <div class="val">{{ format_money($booking->total) }}</div>
                    </div>
                    @if ($booking->status != 'draft')
                        <div class="d-flex justify-content-between">
                            <div class="label">{{ __('Paid:') }}</div>
                            <div class="val">{{ format_money($booking->paid) }}</div>
                        </div>
                        @if ($booking->paid < $booking->total)
                            <div class="d-flex justify-content-between">
                                <div class="label">{{ __('Remain:') }}</div>
                                <div class="val">{{ format_money($booking->total - $booking->paid) }}</div>
                            </div>
                        @endif
                    @endif
                </li>
                @include ('Booking::frontend/booking/checkout-deposit-amount')
            </ul>
        </div>
    </div>
</div>