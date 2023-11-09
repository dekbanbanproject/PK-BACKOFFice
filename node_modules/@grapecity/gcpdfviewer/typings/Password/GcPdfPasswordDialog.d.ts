/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component, FormEvent } from 'react';
import PdfReportPlugin from '../plugin';
export declare type GcPdfPasswordDialogState = {
    inputValue: string;
    lastShowReason: number;
};
export declare class GcPdfPasswordDialog extends Component<any, GcPdfPasswordDialogState> {
    rootElement: HTMLDivElement;
    _updateCallback: any;
    _wrongPasswordMessage: HTMLSpanElement;
    _cancelCallback: any;
    _plugin: PdfReportPlugin;
    constructor(props: any);
    get inputElement(): HTMLInputElement;
    get wrongPassword(): HTMLElement;
    onFormSubmit(event: FormEvent<HTMLFormElement>): boolean;
    get isVisible(): boolean;
    submitPassword(): void;
    cancel(): void;
    show(plugin: PdfReportPlugin, updateCallback: any, cancelCallback: any, reason?: any): void;
    hide(): void;
    onInputChange(e: any): void;
    onKeyUp(e: any): void;
    get in17n(): i18n | undefined;
    render(): JSX.Element;
}
