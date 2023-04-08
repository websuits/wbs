"use client";

/**
 * Import react dependencies
 */
import {ReactElement, ReactNode} from "react";

/**
 * Render the NavigationMenu component.
 *
 * Recursively displays a menu and its children.
 */
function NavigationMenu({items,}: { items: NavigationItemProps[]; }): ReactElement | null {
    if (!items || !items?.length) {
        return null;
    }

    return (
        <>
            {items.map((item, index) => {
                return (
                    <li>
                        {item.label}
                    </li>
                );
            })}
        </>
    );
}

export interface NavigationItemProps {
    /** Item label. */
    label: string | ReactElement | ReactNode;
    /** After label. */
    afterLabel?: string | ReactElement | ReactNode;
    /** Before label. */
    beforeLabel?: string | ReactElement | ReactNode;
    /** Item path. */
    path: string;
    /** Link target. */
    target?: string;
    /** Link click handler. */
    onLinkClick?: (
        event: React.MouseEvent<Element, MouseEvent>,
        itemIndex: number,
        item: NavigationItemProps
    ) => void;
    /** li mouse enter handler. */
    onLiMouseEnter?: (index: number, item: NavigationItemProps) => void;
    /** li mouse leave handler. */
    onLiMouseLeave?: (index: number, item: NavigationItemProps) => void;
    /** li class name. */
    liClassName?: string;
    /** Link class name. */
    linkClassName?: string;
    /** Determines if child menu items should be displayed. */
    showChildren?: boolean;
    /** Children */
    children?: {
        items: NavigationItemProps[];
        className?: string;
        Title?: ReactElement | ReactNode;
    };
}

/**
 * Render the Navigation component.
 */
export default function NavigationRoot({
   items,
   className,
   listClassName,
   ...props
}: NavigationRootProps): ReactElement {
    return (
        <>
            {!!items?.length && (
                <nav {...props}>
                    <ul>
                        <NavigationMenu items={items}/>
                    </ul>
                </nav>
            )}
        </>
    );
}

export interface NavigationRootProps {
    /** Array of menu items. */
    items?: NavigationItemProps[];
    /** Navigation container class name. */
    className?: string;
    /** List container class name. */
    listClassName?: string;
    /** Inline container styles */
    style?: {};
}
