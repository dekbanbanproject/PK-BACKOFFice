import { AnnotationBase } from "../Annotations/AnnotationTypes";
export declare type LogLevel = 'None' | 'Critical' | 'Error' | 'Warning' | 'Information' | 'Debug' | 'Trace';
export declare enum ModificationType {
    NoChanges = 0,
    Structure = 1,
    RemoveAnnotation = 2,
    AddAnnotation = 3,
    UpdateAnnotation = 4,
    Undo = 5,
    Redo = 6
}
export declare type UndoChangeName = 'form' | 'annotation' | 'rotation' | 'document-structure' | 'optional-content-config';
export declare type PageModification = {
    width?: number;
    height?: number;
    rotate?: number;
    tabs?: "S" | "R" | "C" | undefined;
};
export declare type PageStructureChange = {
    pageIndex: number;
    add: boolean;
    checkNumPages: number;
    modOnly?: boolean;
    mod?: PageModification;
};
export declare type StructureChanges = {
    resultStructure: number[];
    structureChanges: PageStructureChange[];
    pdfInfo: {
        numPages: number;
        fingerprint: string;
    };
    touchedAnnotations?: {
        pageIndex: number;
        annotationId: string;
    }[];
};
export declare type ModificationsState = {
    newAnnotations: {
        pageIndex: number;
        annotation: AnnotationBase;
    }[];
    updatedAnnotations: {
        pageIndex: number;
        annotation: AnnotationBase;
    }[];
    removedAnnotations: {
        pageIndex: number;
        annotationId: string;
    }[];
    structure?: StructureChanges;
    lastChangeType: ModificationType;
    undoCount: number;
    undoIndex: number;
    version: number;
};
export declare type SharedDocumentInfo = {
    accessMode: SharedAccessMode;
    documentId: string;
    fileName: string;
    ownerUserName: string;
    userName: string;
};
export declare enum SharedAccessMode {
    Loading = -1,
    AccessDenied = 0,
    ViewOnly = 1,
    ViewAndEdit = 2
}
export declare type UserAccess = {
    userName: string;
    accessMode: SharedAccessMode;
};
