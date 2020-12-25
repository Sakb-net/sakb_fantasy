<div class="dropdown fl-right noti-dropdown"> 
    <a class="dropdown-toggle"  id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        @if($cuRRlocal == 'ar')
            <img class="flags-img" src="{{ asset('images/icon/ksa.png') }}" />
            <span class="flags-name langsite-larg">{{trans('app.Arabic')}}</span>
        @elseif($cuRRlocal == 'en')
            <img class="flags-img" src="{{ asset('images/baims/usa.png') }}"  />
            <span class="flags-name langsite-larg">{{trans('app.English')}}</span>
        @endif
    </a>
    <ul class="dropdown-menu cart-menu flags-drop" aria-labelledby="dropdownMenu2">
        @if($cuRRlocal != 'ar')
        <li ><a class="changeLanguage" id="" data-val="ar">
                <img class="flags-img" src="{{ asset('images/icon/ksa.png') }}"  />
                {{trans('app.lang')}} {{trans('app.Arabic')}}
            </a>
        </li>
        @elseif($cuRRlocal != 'en')
        <li ><a class="changeLanguage" id="" data-val="en">
                <img class="flags-img" src="{{ asset('images/baims/usa.png') }}"  />
                {{trans('app.lang')}} {{trans('app.English')}}
            </a>
        </li>
        @endif
    </ul>
</div>
