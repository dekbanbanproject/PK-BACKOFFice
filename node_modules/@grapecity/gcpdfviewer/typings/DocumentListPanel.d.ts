/// <reference path="vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { ReportViewer, PluginModel } from '@grapecity/viewer-core';
import { GcPdfViewer } from './GcPdfViewer';
import 'whatwg-fetch';
declare type ReportInfo = {
    name: string;
    path: string;
    title: string;
};
declare type Model = {
    reports: ReportInfo[];
    selectedPath?: string;
};
declare type Props = {
    openReport: (name: string) => void;
    viewer: GcPdfViewer;
};
export declare function fetchDocumentList(pdfViewer: GcPdfViewer, documentListUrl?: string): void;
export declare class DocumentList extends Component<Props, Model> {
    private _mounted;
    private _selectedPathVariant;
    constructor(props: Props, context: any);
    componentWillUnmount(): void;
    componentDidMount(): void;
    get selectedPath(): string;
    set selectedPath(selectedPath: string);
    set selectedPathVariant(selectedPathVariant: string);
    onSelect: ({ path }: {
        path: any;
    }) => () => void;
    render(): JSX.Element;
}
declare function addDocumentListPanel(host: ReportViewer): PluginModel.PanelHandle;
export default addDocumentListPanel;
