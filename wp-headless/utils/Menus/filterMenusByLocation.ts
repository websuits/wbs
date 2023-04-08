import formatHeirarchialMenu from "./formatHeirarchialMenu";

export default function filterMenusByLocation(
  menus: any[] | undefined,
  locations: any[]
) {
  if (typeof menus === "undefined") {
    return undefined;
  }

  const data = {} as any;

  // Loop each menu location.
  locations.forEach((location) => {
    // Rename menu types according to Wordpress naming convention ( PRIMARY, FOOTER )
    const locationName = location.replace(/-.*/g, "");

    // Filter menus array by location and assign to new object.
    const wpmenu = menus.filter(function (menu) {
      return menu["locations"].includes(locationName.toUpperCase());
    });

    // Format the returned menu.
    data[locationName] = formatHeirarchialMenu(wpmenu[0]?.menuItems?.nodes);
  });

  return data;
}
