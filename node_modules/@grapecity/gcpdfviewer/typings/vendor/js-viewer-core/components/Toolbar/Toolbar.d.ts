//@ts-ignore
import { Icon } from '@grapecity/core-ui';
/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { ReportViewer, ChangedEventArgs } from '../../control';
import { ViewerStatus, ZoomSettings, MouseMode } from '../DocumentViewer';
/// <reference path="../../../../vendor/i18next.d.ts" />
//@ts-ignore
import i18next from 'i18next';
export declare type ToolbarItem = {
    key: string;
    iconCssClass?: string;
    icon?: Icon;
    text?: string;
    title?: string;
    checked?: boolean;
    enabled?: boolean;
    action?: (curr?: ToolbarItem) => Partial<ToolbarItem> | void;
    onUpdate?: (args: ChangedEventArgs, curr: ToolbarItem) => Partial<ToolbarItem> | void;
};
export declare type ToolbarLayout = {
    default?: string[];
    fullscreen?: string[];
    mobile?: string[];
};
export interface ToolbarViewProps {
    viewer: ReportViewer;
    i18n: i18next.WithT;
}
declare const canGoHistoryDefault: {
    Parent: boolean;
    Back: boolean;
    Fwd: boolean;
};
interface ToolbarViewState {
    readonly items: ToolbarItem[];
    readonly layout?: ToolbarLayout;
    readonly status: ViewerStatus;
    readonly fullscreen: boolean;
    readonly zoom: ZoomSettings;
    readonly mouseMode: MouseMode;
    readonly navigation: {
        readonly hasView: boolean;
        readonly canGoHistory: typeof canGoHistoryDefault;
        readonly canRefresh: boolean;
        readonly canGoForward: boolean;
        readonly canGoBackward: boolean;
        readonly pageIndex: number;
        readonly pageCount: number;
    };
}
export declare class ToolbarView extends Component<ToolbarViewProps, ToolbarViewState> {
    private readonly _commands;
    private _lastViewerState;
    private _toolbar;
    private _isMobile;
    constructor(props: ToolbarViewProps);
    state: ToolbarViewState;
    componentDidUpdate(prevProps: ToolbarViewProps, prevState: ToolbarViewState): void;
    private refreshToolbar;
    private generateUpdateObject;
    private onStateChange;
    private onNavigationBtnClick;
    private onHistoryBtnClick;
    private onPageInputFocus;
    private navigateTo;
    private onPageInputBlur;
    private onPageInputPress;
    private onRefresh;
    private onFullscreenToggle;
    private onZoomChange;
    private onSetMouseMode;
    addItem: (item: ToolbarItem) => void;
    removeItem: (key: string) => void;
    updateItem: (key: string, itemUpdate?: Partial<ToolbarItem> | undefined) => void;
    private onButtonItemClick;
    private getItemsDictionary;
    updateLayout: (layout: ToolbarLayout) => void;
    static builtinToolbar: string[];
    getDefaultToolbarItems: () => string[];
    recalculateBounds: () => void;
    render(): JSX.Element;
}
export {};
