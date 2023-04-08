import filterMenusByLocation from "../../utils/Menus/filterMenusByLocation";
import formatNavigationMenu from "../../utils/Menus/formatNavigationMenu";
import { NavigationItemProps } from "./Navigation.props";
import menuLocations from "./Navigation.config";

export default function Navigation({ menus }) {
    const allMenus = getMenus(menus);

    const primaryMenu = formatNavigationMenu(
        allMenus?.primary
    ) as NavigationItemProps[];

    console.log(primaryMenu);
    return (
        <>
            {primaryMenu.map((item) => {
                return (
                    <a href={item.path}>
                        {item.label}
                    </a>
                );
            })}
        </>
    )
}

function getMenus(
    menus: { nodes?: [] } | undefined,
    locations: string[] = menuLocations
) {
    if (locations.length === 0) {
        return []; // Exit if empty.
    }

    // Filter returned menus by specific menu location.
    const filteredMenus = filterMenusByLocation(menus?.nodes, locations);

    // assert non-null return value
    return filteredMenus || {};
}
