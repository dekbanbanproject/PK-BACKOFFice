/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { BaseToolProps, SignToolModel } from './types';
import { SignToolSettings } from '../ViewerOptions';
export declare class DrawTool extends Component<BaseToolProps, SignToolModel> {
    private _mounted;
    private _canvas;
    private _signaturePad;
    private _dropdown;
    private _isDirty;
    private _onKeyDownHandler;
    constructor(props: BaseToolProps, state: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    onKeyDown(e: KeyboardEvent): void;
    render(): JSX.Element;
    private renderToolbar;
    get canvasSize(): {
        h: number;
        w: number;
    };
    get isMounted(): boolean;
    get hasUndo(): boolean;
    get penColor(): string;
    set penColor(penColor: string);
    get penWidth(): number;
    set penWidth(penWidth: number);
    get hideToolbar(): boolean;
    get settings(): SignToolSettings;
    setSetting(settingName: string, value: any): void;
    toDataURL(): string;
    toImageData(): Promise<Uint8Array | null>;
    clear(): void;
    markDirty(isDirty: boolean): void;
    undo(): void;
    repaintCanvas(): void;
    private onColorSelect;
    private _clearInternal;
}
