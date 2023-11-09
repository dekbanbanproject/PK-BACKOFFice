/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { SoundAnnotation } from '../AnnotationTypes';
import { GcPdfViewer } from '../../GcPdfViewer';
import { SetMultiplePropertiesFn } from '../types';
export declare type SoundFileEditorProps = PropertyEditorProps & {
    in17n: i18n;
    setProperties: SetMultiplePropertiesFn;
};
export declare class SoundFileEditor extends Component<SoundFileEditorProps, any> {
    private _fileInput;
    private _fileReader;
    static pendingFileDialog: boolean;
    get pageIndex(): number;
    get originalNode(): SoundAnnotation;
    get viewer(): GcPdfViewer;
    get hasData(): boolean;
    get fileData(): Uint8Array | null;
    get inputFileName(): string;
    get audioProperties(): {
        numChannels: number;
        sampleRate: number;
        bytesPerSample: number;
        subchunk2Size: number;
    };
    get soundBytes(): Uint8Array;
    componentDidMount(): void;
    render(): JSX.Element;
    renderFileSizeLabel(): JSX.Element;
    private _onRemoveAttachmentClick;
    removeAttachment(): void;
    private _onDownloadClick;
    private _onSelectFileClick;
    private _onChange;
}
