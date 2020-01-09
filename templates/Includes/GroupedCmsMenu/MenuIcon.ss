<% if $IconClass %>
    <span class="menu__icon $IconClass"></span>
<% else_if $HasCSSIcon %>
    <span class="menu__icon icon icon-16 icon-{$HasCSSIcon}">&nbsp;</span>
<% else %>
    <span class="menu__icon font-icon-plus-circled">&nbsp;</span>
<% end_if %>