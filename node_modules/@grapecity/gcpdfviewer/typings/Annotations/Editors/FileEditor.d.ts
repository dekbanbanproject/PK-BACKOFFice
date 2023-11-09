/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { FileAttachmentAnnotation } from '../AnnotationTypes';
import { GcPdfViewer } from '../../GcPdfViewer';
import { SetMultiplePropertiesFn } from '../types';
export declare type FileEditorProps = PropertyEditorProps & {
    in17n: i18n;
    setProperties: SetMultiplePropertiesFn;
};
export declare class FileEditor extends Component<FileEditorProps, any> {
    static pendingFileDialog: boolean;
    private _fileReader;
    private _fileInput?;
    constructor(props: any, context: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    get pageIndex(): number;
    get originalNode(): FileAttachmentAnnotation;
    get viewer(): GcPdfViewer;
    hasData(): boolean;
    get fileData(): Uint8Array | null;
    get inputFileName(): string;
    render(): JSX.Element;
    renderFileSizeLabel(): JSX.Element;
    private _onRemoveAttachmentClick;
    private _onDownloadClick;
    get fileInput(): HTMLInputElement;
    private selectLocalFile;
    private checkPropertyDescriptor;
    private _onChange;
}
