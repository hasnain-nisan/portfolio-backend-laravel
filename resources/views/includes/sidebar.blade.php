<style>
    .menu-item.active:before {
        margin-top: 2px;
    }
</style>

<!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
        <a class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
            <p 
                class="h1" 
                style="
                    text-shadow: 1px 1px 2px black;
                    font-size: 35px;
                    margin: 15px 15px 18px -2px;
                "
            >
                ππΈπ»π½π―πΈπ΅π²πΈ
            </p>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item @if (Route::currentRouteName() == 'home') active @endif">
                <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>
            <li class="menu-item @if (Route::currentRouteName() == 'hero') active @endif">
                <a href="{{ route('hero') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-podcast"></i>
                <div data-i18n="Analytics">Hero</div>
                </a>
            </li>
            <li class="menu-item @if (Route::currentRouteName() == 'about') active @endif">
                <a href="{{ route('about') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-info-circle"></i>
                <div data-i18n="Tables">Abouts</div>
                </a>
            </li>
            <li class="menu-item @if (Route::currentRouteName() == 'experiences') active @endif">
                <a href="{{ route('experiences') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div data-i18n="Tables">Experiences</div>
                </a>
            </li>
            <li class="menu-item @if (Route::currentRouteName() == 'skills') active @endif">
                <a href="{{ route('skills') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-fingerprint"></i>
                <div data-i18n="Tables">Skills</div>
                </a>
            </li>
            {{-- <li class="menu-item">
                <a href="tables-basic.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div data-i18n="Tables">Work Experiences</div>
                </a>
            </li> --}}
            <li class="menu-item @if (Route::currentRouteName() == 'works') active @endif">
                <a href="{{ route('works') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-laptop"></i>
                <div data-i18n="Tables">Works</div>
                </a>
            </li>
            <li class="menu-item @if (Route::currentRouteName() == 'technology-stack') active @endif">
                <a href="{{ route('technology-stack') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-coin-stack"></i>
                <div data-i18n="Tables">Technology Stack</div>
                </a>
            </li>
            <li class="menu-item @if (Route::currentRouteName() == 'contacts') active @endif">
                <a href="{{ route('contacts') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-mail-send"></i>
                <div data-i18n="Tables">Contact</div>
                <span class="badge badge-center rounded-pill bg-danger" style="margin-left: 5px;" id="unreadContacts">{{ $unread_contacts }}</span>
                </a>
            </li>
        </ul>
    </aside>
<!-- / Menu -->