/**
 * Import type definitions
 */
import { MenuItemProps } from "./menuItemsProps";

export default function formatNavigationMenu(menuItems: MenuItemProps[]): {} {
  return menuItems?.map((item: MenuItemProps) => {
    const { children, ...itemProps } = item;

    return {
      ...itemProps,
      children: children
        ? {
            items: formatNavigationMenu(children),
          }
        : [],
    };
  });
}
