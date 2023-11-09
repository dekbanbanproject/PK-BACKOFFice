/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import React from 'react';
import { AnnotationsModel, LayoutMode } from './types';
import { AnnotationBase } from './AnnotationTypes';
//@ts-ignore
import { PropertyDescriptor, BoolEditorLocalization, PlainTextEditorLocalization, CollectionEditorLocalization } from '@grapecity/core-ui';
import { ColorEditorLocalization } from './Editors/ColorEditor/types';
import { CalloutLineEditorLocalization } from './Editors/CalloutLineEditor';
import PdfReportPlugin from '../plugin';
import { ParentIdEditorLocalization } from './Editors/ParentIdEditor';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { KeyValuePairEditorLocalization } from './Editors/KeyValuePairEditor';
export declare type GcPdfViewerEditorsLocalization = {
    plainTextEditor: PlainTextEditorLocalization;
    numberEditor: PlainTextEditorLocalization;
    nullableNumberEditor: PlainTextEditorLocalization;
    floatEditor: PlainTextEditorLocalization;
    calloutLineEditor: CalloutLineEditorLocalization;
    keyValuePairEditor: KeyValuePairEditorLocalization;
    collectionEditor: CollectionEditorLocalization;
    parentIdEditor: ParentIdEditorLocalization;
    colorEditor: ColorEditorLocalization;
    boolEditor: BoolEditorLocalization;
    textAreaEditor?: {
        typeTextHere: string;
        cancelBtn: string;
        okBtn: string;
        cancelBtnTitle: string;
        okBtnTitle: string;
        editBtn: string;
    };
    jsCodeAreaEditor?: {
        typeCodeHere: string;
        cancelBtn: string;
        okBtn: string;
        cancelBtnTitle: string;
        okBtnTitle: string;
        editBtn: string;
    };
};
export declare type AnnotationProps = {
    coordinatesOrigin: 'TopLeft' | 'BottomLeft';
    plugin: PdfReportPlugin;
    navigatePage: (pageIndex: number) => void;
    navigateAnnotation: (pageIndex: number, annotation: AnnotationBase, params?: {
        preserveExpanded?: boolean;
        preserveFocused?: boolean;
        toggle?: boolean;
    }) => void;
    addAnnotation: (pageIndex: number, annotation: AnnotationBase) => Promise<{
        pageIndex: number;
        annotation: AnnotationBase;
    }>;
    updateAnnotation: (pageIndex: number, annotation: AnnotationBase, skipExpand: boolean) => Promise<{
        pageIndex: number;
        annotation: AnnotationBase;
    }>;
    updateAnnotations: (pageIndex: number, annotations: AnnotationBase[]) => Promise<{
        pageIndex: number;
        annotations: AnnotationBase[];
    }>;
    removeAnnotation: (pageIndex: number, id: string) => void;
};
export declare class Annotations extends React.Component<AnnotationProps, AnnotationsModel> {
    private static _pendingScrollAnnotationId?;
    private static _pendingScrollAnnotationTime?;
    _outerElement: HTMLElement;
    protected in17n: i18n;
    private _focusedEdtorElement;
    private _needRememberFocusedElement;
    private _onWindowMouseMoveHandler;
    private _onWindowMouseUpHandler;
    private _isMounted;
    private _modernUpdatingInternal;
    constructor(props: any, context: any);
    shouldComponentUpdate(_nextProps: any, _nextState: any, _nextContext: any): boolean;
    getSnapshotBeforeUpdate(prevProps: Readonly<any>, prevState: Readonly<any>): null;
    componentDidUpdate(): void;
    componentDidMount(): void;
    componentWillUnmount(): void;
    get isMounted(): boolean;
    render(): JSX.Element;
    protected _renderAnnotations(pageIndex: number, pageAnnotations: AnnotationBase[], expandedPageIndex: number, expandedAnnotationIds: any, selectedAnnotationId: string, layoutMode: LayoutMode): JSX.Element | null;
    _renderPropertiesPanel(pageIndex: number, node: AnnotationBase, nodes: AnnotationBase[], isSelectedAnnotation: boolean, annotationButton: JSX.Element, deleteBtn: JSX.Element, cloneBtn: JSX.Element, resetContentBtn: JSX.Element | null): JSX.Element;
    setProperties(pageIndex: number, node: AnnotationBase, descriptors: PropertyDescriptor[], values: any[]): Promise<void>;
    setProperty(pageIndex: number, node: AnnotationBase, descriptor: PropertyDescriptor, value: any, skipUpdate?: boolean): Promise<void>;
    private _isModernStateEditor;
    private _switchToCustomAppearanceIfNeeded;
    _updateWithPopupIfAny(pageIndex: number, node: AnnotationBase, descriptor: PropertyDescriptor, updatedValue: any): Promise<boolean>;
    protected _onCloneClick: (event: Event | undefined, pageIndex: number, node: AnnotationBase) => () => true | undefined;
    protected _onDeleteClick: (event: any, pageIndex: number, node: AnnotationBase) => () => true | undefined;
    protected _onResetRedactClick: (event: Event | undefined, pageIndex: number, node: AnnotationBase) => () => boolean;
    protected _onApplyRedactClick: (event: Event | undefined, pageIndex: number, node: AnnotationBase) => () => boolean;
    protected _onResetConvertToContentClick: (event: Event | undefined, pageIndex: number, node: AnnotationBase) => () => boolean;
    protected _onApplyConvertToContentClick: (event: Event | undefined, pageIndex: number, node: AnnotationBase) => () => boolean;
    protected _onPageButtonClick: (pageIndex: number) => () => void;
    protected _onPageChevronButtonClick: (pageIndex: number) => () => void;
    protected _onPropertiesPanelClick: (pageIndex: number, node: AnnotationBase) => () => void;
    protected _onAnnotationClick: (pageIndex: number, node: AnnotationBase) => () => void;
    protected _onAnnotationChevronClick: (pageIndex: number, node: AnnotationBase) => () => void;
    protected static _dragStartInfo?: DragInfo;
    private _onItemButtonMouseDown;
    protected _onAnnotationsOuterMouseMove(event: MouseEvent | TouchEvent): void;
    private _removeDragSourceClass;
    private _onAnnotationsOuterMouseUp;
    ensureHighlightClass(): void;
    private _unbindDragEvents;
    private _resetDragPositions;
    private _consumedClickTime;
    consumeClickEvent(): void;
    get isClickEventConsumed(): boolean;
    static onBeforeEditAnnotation(id: string): void;
    static setScrollAnnotationIntoViewOnRender(id: string): void;
    private _getPendingScrollAnnotationId;
    private _prepareAnnotationProperties;
    protected _getAnnotationIconKey(node: AnnotationBase, redactApplied?: boolean, convertToContentApplied?: boolean): {
        annotationIconKey: string;
        defaultAnnotationIconKey: string;
    };
    protected _getAnnotationButtonTitle(node: AnnotationBase): string;
    private _getAnnotationButtonLabelInternal;
    protected _getAnnotationButtonLabel(node: AnnotationBase): string;
    private _rememberFocusedElement;
    private _restoreFocusedElement;
    private _forgetFocusedElement;
}
declare class DragInfo {
    owner: Annotations;
    pageIndex: number;
    node: AnnotationBase;
    nodes: AnnotationBase[];
    resultOrder: any;
    resultOrderChanged: boolean;
    constructor(owner: Annotations, pageIndex: number, node: AnnotationBase, nodes: AnnotationBase[]);
    get elem(): HTMLElement;
    startDragging(): void;
    finish(): void;
    pageY?: number;
    dragging: boolean;
}
export {};
