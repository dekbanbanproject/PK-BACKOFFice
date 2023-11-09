/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { BaseToolProps, ImageToolModel } from './types';
import { SignToolSettings } from '../ViewerOptions';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare class ImageTool extends Component<BaseToolProps, ImageToolModel> {
    private _mounted;
    private _canvas;
    private _fileInput;
    private _pendingImageSelect;
    private _img?;
    private _isDirty;
    constructor(props: BaseToolProps, state: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    selectImage(): void;
    get in17n(): i18n;
    render(): JSX.Element;
    get isMounted(): boolean;
    set hasImage(hasImage: boolean);
    get hasImage(): boolean;
    get settings(): SignToolSettings;
    setSetting(settingName: string, value: any): void;
    clearCanvas(): void;
    redrawImage(): void;
    repaintCanvas(): void;
    fitWithAspectRatio(srcSize: {
        w: number;
        h: number;
    }, destSize: {
        w: number;
        h: number;
    }): {
        x: number;
        y: number;
        w: number;
        h: number;
    };
    get canvasSize(): {
        h: number;
        w: number;
    };
    toImageData(): Promise<Uint8Array | null>;
    markDirty(isDirty: boolean): void;
    private _onChange;
}
