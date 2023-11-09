/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { AttachmentsModel } from './types';
import PdfReportPlugin from '../plugin';
import { FileAttachmentAnnotation } from '../Annotations/AnnotationTypes';
export declare type AttachmentsProps = {
    navigate: (node: FileAttachmentAnnotation) => void;
    navigatePdf: (node: FileAttachmentAnnotation) => void;
    plugin: PdfReportPlugin;
};
export declare class Attachments extends Component<AttachmentsProps, AttachmentsModel> {
    private onItemClick;
    private onPdfClick;
    private renderAttachment;
    render(): JSX.Element;
}
