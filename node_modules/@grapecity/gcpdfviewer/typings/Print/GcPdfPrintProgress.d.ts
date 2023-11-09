/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
export declare class GcPdfPrintProgress extends Component<any, {
    percentage: number;
    maxValue: number;
}> {
    rootElement: HTMLDivElement;
    _cancel: any;
    constructor(props: any);
    set maxValue(max: number);
    set value(val: number);
    cancel(): void;
    show(cancelCallback: any): void;
    hide(): void;
    render(): JSX.Element | null;
}
