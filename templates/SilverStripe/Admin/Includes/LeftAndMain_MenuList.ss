<ul class="cms-menu__list">
<% loop $GroupedMainMenu %>
    <li class="$LinkingMode $FirstLast<% if $Children %> children <% end_if %><% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
        <a href="$Link" $AttributesHTML>
            <% if $Children %>
                <% if $IconClass %>
                    <span class="menu__icon $IconClass"></span>
                <% else_if $HasCSSIcon %>
		    <span class="menu__icon icon icon-16 icon-{$HasCSSIcon}">&nbsp;</span>
                <% else %>
                    <span class="menu__icon font-icon-plus-circled">&nbsp;</span>
                <% end_if %>
                <span class="text">$Title</span>
            <% else %>
                <span class="menu__icon $IconClass"></span>
                <span class="text">$Title</span>
            <% end_if %>
        </a>
        <% if $Children %>
            <ul class="group">
            <% loop $Children %>
            <li class="$LinkingMode <% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
                <a href="$Link" $AttributesHTML>
                    <span class="menu__icon $IconClass"></span>
                    <span class="text">$ChildTitle</span>
                </a>
            </li>
            <% end_loop %>
            </ul>
        <% end_if %>
    </li>
<% end_loop %>
</ul>
