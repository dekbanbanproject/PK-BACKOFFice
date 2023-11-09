/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { FormFillerDialogProps, FormFillerDialogModel } from "./types";
import GcPdfViewer from "..";
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare class FormFillerDialog extends Component<FormFillerDialogProps, FormFillerDialogModel> {
    private _hidePromise?;
    private _resolve?;
    private _viewer;
    state: {
        enabled: boolean;
        showModal: boolean;
        fields: any;
        isChanged: boolean;
    };
    private _dirtyHash;
    private _formFiller;
    show(viewer: GcPdfViewer): Promise<void>;
    loadFormFields(): void;
    onApply(): void;
    hide(): void;
    render(): JSX.Element;
    private _resolveHidePromise;
    get in17n(): i18n;
}
