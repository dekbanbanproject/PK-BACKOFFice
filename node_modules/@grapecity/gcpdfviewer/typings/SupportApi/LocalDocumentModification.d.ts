export declare type LocalDocumentModification = {
    renderInteractiveForms?: boolean;
    rotation?: number;
    formData?: any;
    annotationsData?: {
        newAnnotations: any[];
        updatedAnnotations: any[];
        removedAnnotations: any[];
    };
    structureChanges?: {
        pageIndex: number;
        add: boolean;
        checkNumPages: number;
    }[];
    annotationsOrderTable: {
        [key: number]: string[];
    };
    optionalContentVisibility: {
        groupId: string;
        groupName: string;
        visible: boolean;
    }[];
};
