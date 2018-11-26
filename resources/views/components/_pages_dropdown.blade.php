@php
    $traverse = function ($parents) use (&$traverse) {
        foreach ($parents as $parent) {
            if ($parent->children->count()) {
                $toggleButton = '<a id="page%sDropdown" class="%s" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">%s <span class="%s"></span></a>';
                $dropdownType = $parent->parent_id ? 'dropdown-submenu' : 'dropdown';
                $togglerStyles = $dropdownType != 'dropdown' ? 'dropdown-item' : 'nav-link dropdown-toggle';
                $caret = $dropdownType == 'dropdown' ? 'caret' : 'float-right fa fa-caret-right';

                echo sprintf('<li class="%s">', $dropdownType);
                    echo sprintf($toggleButton, $parent->id, $togglerStyles, $parent->title, $caret);
                    echo '<ul class="dropdown-menu">';
                        $traverse($parent->children);
                    echo '</ul>';
                echo '</li>';
            } else {
                $itemStyle = !$parent->parent_id ? 'nav-item' : 'dropdown-item';
                $active = return_if(on_page($parent->uri, 'url'), 'active');
                $styles = join(" ", [!$parent->parent_id ? 'nav-link' : 'dropdown-item', $active]);
                echo sprintf(
                    '<li class="%s"><a href="%s" class="%s">%s</a></li>',
                    $itemStyle,
                    $parent->prettyUri,
                    $styles,
                    $parent->title
                );
            }
        }
    };

    $traverse($pages);
@endphp
