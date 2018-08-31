<ul class="cms-menu__list">
    <% loop $MainMenu %>
        <li class="$LinkingMode $FirstLast <% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
            <a href="$Link" $AttributesHTML>
                <% if $Children %>

                    <% if $IconClass %>
                        <span class="menu__icon $IconClass"></span>
                    <% else %>
                        <span class="menu__icon font-icon-circle-star font-icon-{$Icon}">&nbsp;</span>
                    <% end_if %>
                    <span class="text">$Title</span>

                    <!--

                    <% if $Icon %>
                        <span class="icon icon-16 icon-$Icon">&nbsp;</span>
                        <span class="text">$Title</span>
                    <% else %>
                        <span class="no-icon text">$Title</span>
                    <% end_if %>
                    -->
                <% else %>
                    <% if $IconClass %>
                        <span class="menu__icon $IconClass"></span>
                    <% else %>
                        <span class="menu__icon font-icon-circle-star font-icon-{$Icon}">&nbsp;</span>
                    <% end_if %>
                    <span class="text">$Title</span>
                <% end_if %>

            </a>

            <% if $Children %>
                <ul>
                    <% loop $Children %>
                        <li class="$LinkingMode $FirstLast" id="Menu-$Code">
                            <a href="$Link" $AttributesHTML>
                                <% if $IconClass %>
                                    <span class="menu__icon $IconClass"></span>
                                <% else %>
                                    <span class="menu__icon font-icon-circle-star font-icon-{$Icon}">&nbsp;</span>
                                <% end_if %>
                                <span class="text">$Title</span>
                            </a>
                        </li>
                    <% end_loop %>
                </ul>
            <% end_if %>

        </li>
    <% end_loop %>
</ul>
