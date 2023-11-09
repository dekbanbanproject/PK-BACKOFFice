/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component, ChangeEvent } from 'react';
import { BaseToolProps, TypeToolModel } from './types';
import { SignToolSettings } from '../ViewerOptions';
export declare class TypeTool extends Component<BaseToolProps, TypeToolModel> {
    private _mounted;
    private _canvas;
    private _dropdown;
    private _textInput;
    private _pendingFocus;
    private _isDirty;
    private _canvasRuler;
    state: TypeToolModel;
    constructor(props: BaseToolProps, state: TypeToolModel);
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    focusInput(): void;
    render(): JSX.Element;
    onTextChange(e: ChangeEvent<HTMLInputElement>): void;
    private renderToolbar;
    get isMounted(): boolean;
    get textColor(): string;
    set textColor(textColor: string);
    get italic(): boolean;
    set italic(italic: boolean);
    get bold(): boolean;
    set bold(bold: boolean);
    set text(text: string);
    get text(): string;
    get fontSize(): number;
    set fontSize(fontSize: number);
    get fontName(): string;
    set fontName(fontName: string);
    get fontNames(): string[];
    get hideToolbar(): boolean;
    get settings(): SignToolSettings;
    setSetting(settingName: string, value: any): void;
    clearText(): void;
    clearCanvas(): void;
    redrawText(): void;
    repaintCanvas(): void;
    markDirty(isDirty: boolean): void;
    get canvasSize(): {
        width: number;
        height: number;
    };
    toImageData(): Promise<Uint8Array | null>;
    private onColorSelect;
}
