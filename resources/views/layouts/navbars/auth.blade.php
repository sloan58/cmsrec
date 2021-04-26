<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="/" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="/img/cms.png">
            </div>
        </a>
        <a href="/" class="simple-text logo-normal">
            {{ __('CMS Rec') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            @if(auth()->user()->isAdmin())
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('home') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            @endif
            <li class="{{ $elementActive == 'recordings' ? 'active' : '' }}">
                <a href="{{ route('recordings.index') }}">
                    <i class="fa fa-video-camera"></i>
                    <p>{{ __('My Recordings') }}</p>
                </a>
            </li>
            @if(auth()->user()->isAdmin())
            <li class="{{ $elementActive == 'cms' ? 'active' : '' }}">
                <a href="{{ route('cms.index') }}">
                    <i class="fa fa-server"></i>
                    <p>{{ __('CMS Servers') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'cospaces' ? 'active' : '' }}">
                <a href="{{ route('cospaces.index') }}">
                    <i class="fa fa-crop"></i>
                    <p>{{ __('CMS CoSpaces') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'users' ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="nc-icon nc-single-02"></i>
                    <p>{{ __('Users') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'settings' ? 'active' : '' }}">
                <a href="{{ route('settings.index') }}">
                    <i class="nc-icon nc-settings-gear-65"></i>
                    <p>{{ __('Settings') }}</p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
