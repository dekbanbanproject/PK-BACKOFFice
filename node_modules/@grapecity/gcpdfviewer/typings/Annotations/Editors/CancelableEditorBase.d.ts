/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
import { GcPdfViewerEditorsLocalization } from './../Annotations';
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare type CancelableEditorProps = PropertyEditorProps & {
    localization: GcPdfViewerEditorsLocalization;
    in17n: i18n;
};
export declare class CancelableEditorBase extends Component<CancelableEditorProps, any> {
    protected _outer: HTMLSpanElement;
    getEditorControls(): JSX.Element[];
    onApply(): void;
    onCancel(): void;
    setControlsVisibility(_isVisible: boolean): void;
    getEditButtonLabel(): string;
    protected isValueDirty(): boolean;
    render(): JSX.Element;
    raiseValueChanged(isDirty: boolean, forceRenderComponent?: boolean): void;
    handleKeyboardEvent(e: KeyboardEvent): boolean;
    finishEdit(): void;
    cancelEdit(): void;
    private _onEditContentsClick;
    private _onApplyClick;
    private _onCancelClick;
    private _updateButtons;
    private _showControls;
}
