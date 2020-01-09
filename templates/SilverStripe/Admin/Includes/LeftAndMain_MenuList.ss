<ul class="cms-menu__list">
<% loop $GroupedMainMenu %>
    <li class="$LinkingMode $FirstLast<% if $Children %> children <% end_if %><% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
        <a href="$Link" $AttributesHTML>
            <% include Includes/GroupedCmsMenu/MenuIcon %>
            <span class="text">$Title</span>
        </a>
        <% if $Children %>
            <ul class="group">
            <% loop $Children %>
            <li class="$LinkingMode <% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
                <a href="$Link" $AttributesHTML>
                    <% include Includes/GroupedCmsMenu/MenuIcon %>
                    <span class="text">$ChildTitle</span>
                </a>
            </li>
            <% end_loop %>
            </ul>
        <% end_if %>
    </li>
<% end_loop %>
</ul>
