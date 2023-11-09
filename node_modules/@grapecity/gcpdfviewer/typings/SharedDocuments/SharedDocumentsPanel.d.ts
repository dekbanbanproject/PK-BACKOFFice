/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { ReportViewer, PluginModel } from '@grapecity/viewer-core';
import { GcPdfViewer } from './../GcPdfViewer';
import { SharedDocumentInfo } from './types';
declare type SharedDocumentsModel = {
    sharedDocuments: SharedDocumentInfo[];
    disabled?: boolean;
};
declare type Props = {
    pdfViewer: GcPdfViewer;
};
export declare class SharedDocuments extends Component<Props, SharedDocumentsModel> {
    private _mounted;
    constructor(props: Props, context: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    fetchSharedDocuments(): void;
    openSharedDocument(documentId: string): Promise<void>;
    render(): JSX.Element;
    renderSharedDocumentListItem(sharedDocument: SharedDocumentInfo): JSX.Element;
}
declare function createSharedDocumentsPanel(host: ReportViewer): PluginModel.PanelHandle;
export default createSharedDocumentsPanel;
