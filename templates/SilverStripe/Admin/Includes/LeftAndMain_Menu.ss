<div class="cms-mobile-menu-toggle-wrapper"></div>

<div class="fill-height cms-menu cms-panel cms-panel-layout" id="cms-menu" data-layout-type="border" aria-expanded="false">
    <div class="cms-menu__header">
        <% include SilverStripe\\Admin\\LeftAndMain_MenuLogo %>
        <% include SilverStripe\\Admin\\LeftAndMain_MenuStatus %>
    </div>

    <div class="flexbox-area-grow panel--scrollable panel--triple-toolbar cms-panel-content">
        <% include SilverStripe\\Admin\\LeftAndMain_MenuList %>
    </div>

    <div class="toolbar toolbar--south cms-panel-toggle vertical-align-items">
        <% include SilverStripe\\Admin\\LeftAndMain_MenuToggle %>
    </div>
</div>

<button class="fill-height fill-width cms-menu-mobile-overlay" aria-controls="cms-menu" aria-expanded="false"></button>




<div class="cms-menu cms-panel cms-panel-layout west" id="cms-menu" data-layout-type="border">
	<div class="cms-logo-header north">
		<div class="cms-logo">
			<a href="$ApplicationLink" target="_blank" title="$ApplicationName (Version - $CMSVersion)">
				$ApplicationName <% if $CMSVersion %><abbr class="version">$CMSVersion</abbr><% end_if %>
			</a>
			<span><% if $SiteConfig %>$SiteConfig.Title<% else %>$ApplicationName<% end_if %></span>
		</div>

		<div class="cms-login-status">
			<a href="$LogoutURL" class="logout-link font-icon-logout" title="<%t LeftAndMain_Menu_ss.LOGOUT 'Log out' %>"></a>
			<% with $CurrentMember %>
				<span>
					<%t LeftAndMain_Menu_ss.Hello 'Hi' %>
					<a href="{$AbsoluteBaseURL}admin/myprofile" class="profile-link">
						<% if $FirstName && $Surname %>$FirstName $Surname<% else_if $FirstName %>$FirstName<% else %>$Email<% end_if %>
					</a>
				</span>
			<% end_with %>
		</div>
	</div>

	<div class="cms-panel-content center">
		<ul class="cms-menu-list">
		<% loop $GroupedMainMenu %>
			<li class="$LinkingMode $FirstLast<% if $Children %> children <% end_if %><% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
				<a href="$Link" $AttributesHTML>
					<% if $Children %>
						<% if $Icon %>
							<span class="icon icon-16 icon-$Icon">&nbsp;</span>
							<span class="text">$Title</span>
						<% else %>
							<span class="no-icon text">$Title</span>
						<% end_if %>
					<% else %>
						<span class="icon icon-16 icon-{$Code.LowerCase}">&nbsp;</span>
						<span class="text">$Title</span>
					<% end_if %>
				</a>
				<% if $Children %>
					<ul>
						<% loop $Children %>
	                        <li class="$LinkingMode $FirstLast" id="Menu-$Code">
	                        	<a href="$Link" $AttributesHTML>
									<span class="icon icon-16 icon-{$Code.LowerCase}">&nbsp;</span>
									<span class="text">$Title</span>
								</a>
	                        </li>
	                    <% end_loop %>
	                </ul>
                <% end_if %>
			</li>
		<% end_loop %>
		</ul>
	</div>

	<div class="cms-panel-toggle south">
		<button class="sticky-toggle" type="button" title="Sticky nav">Sticky nav</button>
		<span class="sticky-status-indicator">auto</span>
		<a class="toggle-expand" href="#"><span>&raquo;</span></a>
		<a class="toggle-collapse" href="#"><span>&laquo;</span></a>
	</div>
</div>
