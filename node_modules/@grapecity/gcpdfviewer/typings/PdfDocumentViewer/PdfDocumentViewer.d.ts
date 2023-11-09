/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
export declare class PdfDocumentViewer extends Component<any, any> {
    private _rightSidebarElement;
    private _pdfPaneOuterElement;
    constructor(props: any, state: any);
    render(): JSX.Element;
    get pdfPaneOuterElement(): HTMLDivElement | null;
    get rightSidebarElement(): HTMLDivElement | null;
}
