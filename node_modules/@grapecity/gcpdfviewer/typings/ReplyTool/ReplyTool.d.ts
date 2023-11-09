/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { AnnotationBase, TextAnnotation } from "../Annotations/AnnotationTypes";
import PdfReportPlugin from '../plugin';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { ReplyToolModel } from './types';
import { GcEditableTextBehavior } from '../Utils/GcEditableTextBehavior';
export declare type ReplyToolProps = {
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
export declare class ReplyTool extends Component<ReplyToolProps, ReplyToolModel> {
    _outerElement: HTMLElement;
    in17n: i18n;
    _editableTextBehavior: GcEditableTextBehavior;
    _selectedNode: any;
    constructor(props: any, context: any);
    render(): JSX.Element;
    private _hideReplyEditor;
    canEditAuthor(node: TextAnnotation): boolean;
    canAddReply(node: TextAnnotation): boolean;
    canDeleteReply(node: TextAnnotation): boolean;
    canStatusReply(node: TextAnnotation): boolean;
    canEditText(node: TextAnnotation): boolean;
    private get replyToolSettings();
    protected _renderComments(pageIndex: number, annotations: AnnotationBase[], expandedPageIndex: number, selectedAnnotationId: string): JSX.Element | null;
    getNodeContents(node: TextAnnotation, annotations: AnnotationBase[]): string;
    renderNoteItemMenu(pageIndex: number, node: TextAnnotation): JSX.Element;
    _onAuthorLabelClick: (pageIndex: number, node: TextAnnotation, isSelected: boolean) => () => void;
    _onNoteTextLabelClick: (pageIndex: number, node: TextAnnotation, isSelected: boolean) => () => void;
    _showInlineTextEditor(textLabel: HTMLElement, pageIndex: number, node: TextAnnotation, propertyKey: 'contents' | 'title'): void;
    _disposeEditableTextBehavior(): void;
    _onNoteItemClick: (pageIndex: number, node: AnnotationBase) => () => boolean;
    _onPageButtonClick: (pageIndex: number) => () => void;
    _onReplyPlaceholderClick: (event: Event | undefined, pageIndex: number, node: AnnotationBase) => () => boolean;
    _onCancelReplyClick: (event: Event | undefined, pageIndex: number, node: TextAnnotation) => () => boolean;
    _onPostReplyClick: (event: Event | undefined, pageIndex: number, node: TextAnnotation) => () => boolean;
    private _cancelReplyInternal;
    private _postReplyInternal;
}
