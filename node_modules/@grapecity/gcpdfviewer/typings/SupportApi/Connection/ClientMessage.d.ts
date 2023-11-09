import { Message } from "./Message";
import { ModificationType, ModificationsState, SharedAccessMode, StructureChanges } from "../../SharedDocuments/types";
import { AnnotationBase } from "../../Annotations/AnnotationTypes";
export declare type ClientMessage = Message & {
    clientId: string;
    type: ClientMessageType | ClientRequestType;
    data?: ClientMessageParameters;
};
export declare enum ClientMessageType {
    Start = 1,
    Stop = 2,
    ShareDocument = 10,
    UnshareDocument = 11,
    Modification = 20,
    Reconnect = 30
}
export declare enum ClientRequestType {
    UserAccessList = 100,
    SharedDocumentsList = 101,
    AllUsersList = 102,
    OpenSharedDocument = 103,
    StartSharedMode = 104,
    StopSharedMode = 105
}
export declare type ClientMessageParameters = StartParameters | StopParameters | ShareDocumentParameters | ModificationParameters | UnshareDocumentParameters | UserAccessListParameters | SharedDocumentsListParameters | OpenSharedDocumentParameters;
export declare type StartParameters = {
    clientId: string;
    userName: string;
};
export declare type StopParameters = {
    clientId: string;
};
export declare type ShareDocumentParameters = {
    documentId: string;
    userName: string;
    accessMode: SharedAccessMode;
    modificationsState: ModificationsState;
};
export declare type UnshareDocumentParameters = {
    documentId: string;
    userName: string;
};
export declare type ModificationParameters = {
    type: ModificationType;
    data?: {
        pageIndex: number;
        annotation: AnnotationBase;
    } | {
        pageIndex: number;
        annotationId: string;
    } | StructureChanges;
};
export declare type UserAccessListParameters = {
    documentId: string;
};
export declare type SharedDocumentsListParameters = {
    userName: string;
};
export declare type OpenSharedDocumentParameters = {
    documentId: string;
};
