//@ts-ignore
import { ToolbarLayout } from '@grapecity/viewer-core';
import { ToolbarButtonKey } from '../Annotations/AnnotationTypes';
export declare type PdfToolbarLayout = {
    viewer: ToolbarLayout;
    annotationEditor: ToolbarLayout;
    formEditor: ToolbarLayout;
    stickyBehavior?: ToolbarButtonKey[];
};
