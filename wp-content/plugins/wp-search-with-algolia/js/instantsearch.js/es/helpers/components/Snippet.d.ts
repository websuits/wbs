
import { h } from 'preact';
import type { BaseHit, Hit, PartialKeys } from '../../types';
import type { SnippetProps as SnippetUiComponentProps } from '../../components/Snippet/Snippet';
export declare type SnippetProps<THit extends Hit<BaseHit>> = {
    hit: THit;
    attribute: keyof THit | string[];
    cssClasses?: SnippetUiComponentProps['classNames'];
} & PartialKeys<Omit<SnippetUiComponentProps, 'parts' | 'classNames'>, 'highlightedTagName' | 'nonHighlightedTagName' | 'separator'>;
export declare function Snippet<THit extends Hit<BaseHit>>({ hit, attribute, cssClasses, ...props }: SnippetProps<THit>): h.JSX.Element;
