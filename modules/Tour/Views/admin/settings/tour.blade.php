<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Page Search")}}</h3>
        <p class="form-group-desc">{{__('Config page search of your website')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-title"><strong>{{__("General Options")}}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="" >{{__("Title Page")}}</label>
                    <div class="form-controls">
                        <input type="text" name="tour_page_search_title" value="{{ setting_item_with_lang('tour_page_search_title',request()->query('lang')) }}" class="form-control">
                    </div>
                </div>
                @if(is_default_lang())
                <div class="form-group">
                    <label class="" >{{__("Banner Page Web")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('tour_page_search_banner',$settings['tour_page_search_banner'] ?? "") !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="" >{{__("Banner Page Mobile")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('tour_page_search_banner_mobile',$settings['tour_page_search_banner_mobile'] ?? "") !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="" >{{__("Layout Search")}}</label>
                    <div class="form-controls">
                        <select name="tour_layout_search" class="form-control" >
                            @foreach(config('tour.layouts',['normal'=>__("Normal Layout"),'map'=>__("Map Layout")]) as $id=>$name))
                                <option value="{{$id}}" {{ setting_item('tour_layout_search','normal') == $id ? 'selected' : ''  }}>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="" >{{__("Location Search Style")}}</label>
                            <div class="form-controls">
                                <select name="tour_location_search_style" class="form-control">
                                    <option {{ ($settings['tour_location_search_style'] ?? '') == 'normal' ? 'selected' : ''  }}      value="normal">{{__("Normal")}}</option>
                                    <option {{ ($settings['tour_location_search_style'] ?? '') == 'autocomplete' ? 'selected' : '' }} value="autocomplete">{{__('Autocomplete from locations')}}</option>
                                    <option {{ ($settings['tour_location_search_style'] ?? '') == 'autocompletePlace' ? 'selected' : '' }} value="autocompletePlace">{{__('Autocomplete from Gmap Place')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="" >{{__("Limit item per Page")}}</label>
                            <div class="form-controls">
                                <input type="number" min="1" name="tour_page_limit_item" placeholder="{{ __("Default: 9") }}" value="{{setting_item_with_lang('tour_page_limit_item',request()->query('lang'), 9)}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" data-condition="tour_location_search_style:is(autocompletePlace)">
                        <label class="" >{{__("Radius options")}}</label>
                        <div class="input-group mb-3">
                            <input type="number" name="tour_location_radius_value" min="0" value="{{ setting_item('tour_location_radius_value',1)}}" class="form-control" >
                            <div class="input-group-append">
                                <select name="tour_location_radius_type" id="">
                                    <option  {{ (setting_item('tour_location_radius_type') ?? '') == 3959 ? 'selected' : ''  }} value="3959">{{__('Miles')}}</option>
                                    <option  {{ (setting_item('tour_location_radius_type') ?? '') == 6371 ? 'selected' : ''  }} value="6371">{{__('Km')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label class="" >{{__("Layout Map Option")}}</label>
                    <div class="form-controls">
                        <select name="tour_layout_map_option" class="form-control">
                            <option {{ (setting_item_with_lang('tour_layout_map_option',request()->query('lang')) ?? '') == 'map_left' ? 'selected' : '' }} value="map_left">{{__('Map Left')}}</option>
                            <option {{ (setting_item_with_lang('tour_layout_map_option',request()->query('lang')) ?? '') == 'map_right' ? 'selected' : ''  }} value="map_right">{{__("Map Right")}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>{{__("Map Lat Default")}}</label>
                        <div class="form-controls">
                            <input type="text" name="tour_map_lat_default" value="{{$settings['tour_map_lat_default'] ?? ''}}" class="form-control" placeholder="21.030513">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>{{__("Map Lng Default")}}</label>
                        <div class="form-controls">
                            <input type="text" name="tour_map_lng_default" value="{{$settings['tour_map_lng_default'] ?? ''}}" class="form-control" placeholder="105.840565">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>{{__("Map Zoom Default")}}</label>
                        <div class="form-controls">
                            <input type="text" name="tour_map_zoom_default" value="{{$settings['tour_map_zoom_default'] ?? ''}}" class="form-control" placeholder="13">
                        </div>
                    </div>
                    <div class="col-md-12 mt-1">
                        <i> {{ __('Get lat - lng in here') }} <a href="https://www.latlong.net" target="_blank">https://www.latlong.net</a></i>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="" >{{__("Icon Marker in Map")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('tour_icon_marker_map',$settings['tour_icon_marker_map'] ?? "") !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @include('Tour::admin.settings.form-search')
        @include('Tour::admin.settings.map-search')
        <div class="panel">
            <div class="panel-title"><strong>{{__("SEO Options")}}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#seo_1">{{__("General Options")}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#seo_2">{{__("Share Facebook")}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#seo_3">{{__("Share Twitter")}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" >
                        <div class="tab-pane active" id="seo_1">
                            <div class="form-group" >
                                <label class="control-label">{{__("Seo Title")}}</label>
                                <input type="text" name="tour_page_list_seo_title" class="form-control" placeholder="{{__("Enter title...")}}" value="{{ setting_item_with_lang('tour_page_list_seo_title',request()->query('lang'))}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Seo Description")}}</label>
                                <input type="text" name="tour_page_list_seo_desc" class="form-control" placeholder="{{__("Enter description...")}}" value="{{setting_item_with_lang('tour_page_list_seo_desc',request()->query('lang'))}}">
                            </div>
                            @if(is_default_lang())
                                <div class="form-group form-group-image">
                                    <label class="control-label">{{__("Featured Image")}}</label>
                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('tour_page_list_seo_image', $settings['tour_page_list_seo_image'] ?? "" ) !!}
                                </div>
                            @endif
                        </div>
                        @php
                            $seo_share = json_decode(setting_item_with_lang('tour_page_list_seo_desc',request()->query('lang'),'[]'),true);
                        @endphp
                        <div class="tab-pane" id="seo_2">
                            <div class="form-group">
                                <label class="control-label">{{__("Facebook Title")}}</label>
                                <input type="text" name="tour_page_list_seo_share[facebook][title]" class="form-control" placeholder="{{__("Enter title...")}}" value="{{$seo_share['facebook']['title'] ?? "" }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Facebook Description")}}</label>
                                <input type="text" name="tour_page_list_seo_share[facebook][desc]" class="form-control" placeholder="{{__("Enter description...")}}" value="{{$seo_share['facebook']['desc'] ?? "" }}">
                            </div>
                            @if(is_default_lang())
                                <div class="form-group form-group-image">
                                    <label class="control-label">{{__("Facebook Image")}}</label>
                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('tour_page_list_seo_share[facebook][image]',$seo_share['facebook']['image'] ?? "" ) !!}
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane" id="seo_3">
                            <div class="form-group">
                                <label class="control-label">{{__("Twitter Title")}}</label>
                                <input type="text" name="tour_page_list_seo_share[twitter][title]" class="form-control" placeholder="{{__("Enter title...")}}" value="{{$seo_share['twitter']['title'] ?? "" }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Twitter Description")}}</label>
                                <input type="text" name="tour_page_list_seo_share[twitter][desc]" class="form-control" placeholder="{{__("Enter description...")}}" value="{{$seo_share['twitter']['title'] ?? "" }}">
                            </div>
                            @if(is_default_lang())
                                <div class="form-group form-group-image">
                                    <label class="control-label">{{__("Twitter Image")}}</label>
                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('tour_page_list_seo_share[twitter][image]', $seo_share['twitter']['image'] ?? "" ) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(is_default_lang())
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Review Options")}}</h3>
        <p class="form-group-desc">{{__('Config review for tour')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group d-none">
                    <label class="" >{{__("Enable review system for Tour?")}}</label>
                    <div class="form-controls">
                        <label><input type="checkbox" name="tour_enable_review" value="1" @if(!empty($settings['tour_enable_review'])) checked @endif /> {{__("Yes, please enable it")}} </label>
                        <br>
                        <small class="form-text text-muted">{{__("Turn on the mode for reviewing tour")}}</small>
                    </div>
                </div>
                <div class="form-group" data-condition="tour_enable_review:is(1)">
                    <label class="" >{{__("Customer must book a tour before writing a review?")}}</label>
                    <div class="form-controls">
                        <label><input type="checkbox" name="tour_enable_review_after_booking" value="1"  @if(!empty($settings['tour_enable_review_after_booking'])) checked @endif /> {{__("Yes please")}} </label>
                        <br>
                        <small class="form-text text-muted">{{__("ON: Only post a review after booking - Off: Post review without booking")}}</small>
                    </div>
                </div>
                <div class="form-group d-none" data-condition="tour_enable_review:is(1),tour_enable_review_after_booking:is(1)">
                    <label>{{__("Allow review after making Completed Booking?")}}</label>
                    <div class="form-controls">
                        @php
                            $status = config('booking.statuses');
                            $settings_status = !empty($settings['tour_allow_review_after_making_completed_booking']) ? json_decode($settings['tour_allow_review_after_making_completed_booking']) : [];
                        @endphp
                        <div class="row">
                            @foreach($status as $item)
                                <div class="col-md-4">
                                    <label><input type="checkbox" name="tour_allow_review_after_making_completed_booking[]" @if(in_array($item,$settings_status)) checked @endif value="{{$item}}"  /> {{booking_status_to_text($item)}} </label>
                                </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">{{__("Pick to the Booking Status, that allows reviews after booking")}}</small>
                        <small class="form-text text-muted">{{__("Leave blank if you allow writing the review with all booking status")}}</small>
                    </div>
                </div>
                <div class="form-group d-none" data-condition="tour_enable_review:is(1)">
                    <label class="" >{{__("Does the review need approved by admin?")}}</label>
                    <div class="form-controls">
                        <label><input type="checkbox" name="tour_review_approved" value="1"  @if(!empty($settings['tour_review_approved'])) checked @endif /> {{__("Yes please")}} </label>
                        <br>
                        <small class="form-text text-muted">{{__("ON: Review must be approved by admin - OFF: Review is automatically approved")}}</small>
                    </div>
                </div>
                <div class="form-group" data-condition="tour_enable_review:is(1)">
                    <label class="" >{{__("Review number per page")}}</label>
                    <div class="form-controls">
                        <input type="number" class="form-control" name="tour_review_number_per_page" value="{{ $settings['tour_review_number_per_page'] ?? 5 }}" />
                        <small class="form-text text-muted">{{__("Break comments into pages")}}</small>
                    </div>
                </div>
                <div class="form-group" data-condition="tour_enable_review:is(1)">
                    <label class="" >{{__("Review criteria")}}</label>
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="g-items-header">
                                <div class="row">
                                    <div class="col-md-5">{{__("Title")}}</div>
                                    <div class="col-md-1"></div>
                                </div>
                            </div>
                            <div class="g-items">
                                <?php
                                if(!empty($settings['tour_review_stats'])){
                                $social_share = json_decode($settings['tour_review_stats']);
                                ?>
                                @foreach($social_share as $key=>$item)
                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <input type="text" name="tour_review_stats[{{$key}}][title]" class="form-control" value="{{$item->title}}" placeholder="{{__('Eg: Service')}}">
                                            </div>
                                                @php
                                                $slug = Str::slug($item->title, '-');
                                                $slug = substr($slug, 0, 60);
                                                $slug .= '-unique-string';
                                                @endphp
                                                <input type="text" name="tour_review_stats[{{$key}}][id]" class="form-control" value="{{$slug}}">
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <?php } ?>
                            </div>
                            <div class="text-right">
                                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                            </div>
                            <div class="g-more hide">
                                <div class="item" data-number="__number__">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" __name__="tour_review_stats[__number__][title]" class="form-control" value="" placeholder="{{__('Eg: Service')}}">
                                        </div>
                                        <div class="col-md-1">
                                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif



@if(is_default_lang())
    <hr>
    <div class="row d-none">
        <div class="col-sm-4">
            <h3 class="form-group-title">{{__("Vendor Options")}}</h3>
            <p class="form-group-desc">{{__('Vendor config for tour')}}</p>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="" >{{__("Tour create by vendor must be approved by admin?")}}</label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="tour_vendor_create_service_must_approved_by_admin" value="1" @if(!empty($settings['tour_vendor_create_service_must_approved_by_admin'])) checked @endif /> {{__("Yes please")}} </label>
                            <br>
                            <small class="form-text text-muted">{{__("ON: When vendor posts a service, it needs to be approved by administrator")}}</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="" >{{__("Allow vendor can change their booking status")}}</label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="tour_allow_vendor_can_change_their_booking_status" value="1" @if(!empty($settings['tour_allow_vendor_can_change_their_booking_status'])) checked @endif /> {{__("Yes please")}} </label>
                            <br>
                            <small class="form-text text-muted">{{__("ON: Vendor can change their booking status")}}</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="" >{{__("Allow vendor can change their booking paid amount")}}</label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="tour_allow_vendor_can_change_paid_amount" value="1" @if(!empty($settings['tour_allow_vendor_can_change_paid_amount'])) checked @endif /> {{__("Yes please")}} </label>
                            <br>
                            <small class="form-text text-muted">{{__("ON: Vendor can change their booking paid amount")}}</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="" >{{__("Allow vendor can add service fee")}}</label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="tour_allow_vendor_can_add_service_fee" value="1" @if(!empty($settings['tour_allow_vendor_can_add_service_fee'])) checked @endif /> {{__("Yes please")}} </label>
                            <br>
                            <small class="form-text text-muted">{{__("ON: Vendor can add service fee")}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(is_default_lang())
<hr>

<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Booking Deposit")}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-title"><strong>{{__("Booking Deposit Options")}}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="form-controls">
                        <label><input type="checkbox" name="tour_deposit_enable" value="1" @if(setting_item('tour_deposit_enable')) checked @endif > {{__('Yes, please enable it')}}</label>
                    </div>
                </div>
                <div class="form-group" data-condition="tour_deposit_enable:is(1)">
                    <label >{{__('Deposit Amount')}}</label>
                    <div class="form-controls">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <input type="number" name="tour_deposit_amount" class="form-control" step="0.1" value="{{old('tour_deposit_amount',setting_item('tour_deposit_amount'))}}" >
                                    <select name="tour_deposit_type"  class="form-control">
                                        <option value="fixed">{{__("Fixed")}}</option>
                                        <option @if(old('tour_deposit_type',setting_item('tour_deposit_type')) == 'percent') selected @endif value="percent">{{__("Percent")}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" data-condition="tour_deposit_enable:is(1)">
                    <label class="" >{{__("Deposit Fomular")}}</label>
                    <div class="form-controls">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="tour_deposit_fomular" class="form-control" >
                                    <option value="default" {{($settings['tour_deposit_fomular'] ?? '') == 'default' ? 'selected' : ''  }}>{{__('Default')}}</option>
                                    <option value="deposit_and_fee" {{ ($settings['tour_deposit_fomular'] ?? '') == 'deposit_and_fee' ? 'selected' : ''  }}>{{__("Deposit amount + Buyer free")}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Disable tour module?")}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-title"><strong>{{__("Disable tour module")}}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="form-controls">
                    <label><input type="checkbox" name="tour_disable" value="1" @if(setting_item('tour_disable')) checked @endif > {{__('Yes, please disable it')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif