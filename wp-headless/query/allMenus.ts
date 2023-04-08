// Query partial: retrieve all menus.
import fetchAPI from "../utils/fetch";

export async function getAllMenus(preview) {
    const data = await fetchAPI(
        `
    query AllMenus {
      menus {
        nodes {
          locations
          menuItems(first: 100) {
            nodes {
              id
              parentId
              label
              path
              target
              title
            }
          }
        }
      }
    }
  `,
    )
    return data
}
