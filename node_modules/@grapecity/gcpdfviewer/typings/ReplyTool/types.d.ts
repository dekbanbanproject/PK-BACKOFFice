import { AnnotationBase } from "../Annotations/AnnotationTypes";
import { LayoutMode } from "../Annotations/types";
export declare type ReplyToolModel = {
    layoutMode?: LayoutMode;
    expandedPageIndex?: number;
    expandedAnnotationIds?: any;
    selectedAnnotationId?: string;
    data: {
        pageIndex: number;
        annotations: AnnotationBase[];
    }[] | null;
};
