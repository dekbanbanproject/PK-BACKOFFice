/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { ProgressDialogSink } from './Types';
import GcPdfViewer from '..';
export declare type ProgressDialogState = {
    showModal: boolean;
    value: number;
    message: string;
};
export declare type ProgressDialogProps = {};
export declare class ProgressDialog extends Component<ProgressDialogProps, ProgressDialogState> {
    private _level?;
    private _title;
    private _viewer;
    private _sink;
    private _shown;
    constructor(props: any, context: any);
    state: {
        showModal: boolean;
        value: number;
        message: string;
    };
    show(viewer: GcPdfViewer, title: string, message: string, level?: "info" | "warning" | "error"): ProgressDialogSink;
    cancel(error?: string | Error): void;
    complete(): void;
    progress(value: number, message: string): void;
    render(): JSX.Element;
}
