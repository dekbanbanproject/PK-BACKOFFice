/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import GcPdfViewer from '../../GcPdfViewer';
import { StampAnnotation } from './../AnnotationTypes';
import { SetMultiplePropertiesFn } from '../types';
export declare type ImageFileEditorProps = PropertyEditorProps & {
    in17n: i18n;
    setProperties: SetMultiplePropertiesFn;
};
export declare class ImageFileEditor extends Component<ImageFileEditorProps, any> {
    private _fileInput?;
    private _fileReader;
    static pendingFileDialog: boolean;
    get pageIndex(): number;
    get originalNode(): StampAnnotation;
    get fileId(): string | undefined;
    get viewer(): GcPdfViewer;
    get hasData(): boolean;
    getFileData(): any;
    componentDidMount(): void;
    componentWillUnmount(): void;
    render(): JSX.Element;
    private _onRemoveAttachmentClick;
    private _onDownloadClick;
    get fileInput(): HTMLInputElement;
    private selectLocalFile;
    private _onChange;
    resetImageSizeAspect(imageDpi?: number): void;
}
