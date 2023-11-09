/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { SignToolDialogProps, SignToolDialogModel, SignToolType } from "./types";
import GcPdfViewer from "..";
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { SignToolSettings } from '../ViewerOptions';
import { SignToolStorage } from './SignToolStorage';
export declare const DEFAULT_CANVAS_WIDTH = 500;
export declare const DEFAULT_CANVAS_HEIGHT = 200;
export declare class SignToolDialog extends Component<SignToolDialogProps, SignToolDialogModel> {
    private _hidePromise?;
    private _resolve?;
    private _viewer;
    private _mounted;
    private _resizeHandler;
    private _currentCanvasSize;
    private _prevWindowSize;
    private _shown;
    constructor(props: any, state: any);
    state: {
        enabled: boolean;
        showModal: boolean;
        isChanged: boolean;
        selectedTab: undefined;
    };
    private _DrawTool;
    private _TypeTool;
    private _openImageFlag;
    private _ImageTool;
    private _signToolStorage;
    private _preferredSettings;
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    _onWindowResize(): void;
    onShow(): Promise<void>;
    onHide(): void;
    updateDialogPosition(): void;
    get saveSignature(): boolean;
    set saveSignature(saveSignature: boolean);
    get tabs(): ('Type' | 'Draw' | 'Image')[];
    set tabs(tabs: ('Type' | 'Draw' | 'Image')[]);
    get selectedTab(): SignToolType;
    set selectedTab(selectedTab: SignToolType);
    get title(): string;
    set title(title: string);
    get convertToContent(): boolean;
    set convertToContent(convertToContent: boolean);
    get subject(): string;
    set subject(subject: string);
    get hideSaveSignature(): boolean;
    set hideSaveSignature(value: boolean);
    get pageIndex(): number;
    set pageIndex(pageIndex: number);
    get canvasSize(): {
        width: number;
        height: number;
    };
    set canvasSize(canvasSize: {
        width: number;
        height: number;
    });
    get autoResizeCanvas(): boolean;
    set autoResizeCanvas(autoResizeCanvas: boolean);
    get imageFileName(): string;
    get settings(): SignToolSettings;
    show(viewer: GcPdfViewer, preferredSettings?: SignToolSettings): Promise<void>;
    setSetting(settingName: string, value: any): void;
    private getSetting;
    getAdjustedCanvasSize(): {
        width: number;
        height: number;
    };
    get destinationScale(): number;
    get destinationSize(): {
        width: number;
        height: number;
    };
    get location(): 'Center' | 'Top' | 'Right' | 'Bottom' | 'Left' | 'TopLeft' | 'TopRight' | 'BottomRight' | 'BottomLeft' | {
        x: number;
        y: number;
    };
    set location(location: 'Center' | 'Top' | 'Right' | 'Bottom' | 'Left' | 'TopLeft' | 'TopRight' | 'BottomRight' | 'BottomLeft' | {
        x: number;
        y: number;
    });
    get exactDestinationLocation(): {
        x: number;
        y: number;
    };
    onApply(): Promise<void>;
    get isVisible(): boolean;
    hide(): void;
    render(): JSX.Element;
    get signToolStorage(): SignToolStorage;
    private _resolveHidePromise;
    get in17n(): i18n;
}
