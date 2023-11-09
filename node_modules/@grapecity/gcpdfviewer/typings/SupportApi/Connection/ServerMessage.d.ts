import { Message } from "./Message";
import { ModificationsState, UserAccess } from "../../SharedDocuments/types";
export declare type ServerMessage = Message & {
    type: ServerMessageType;
    data?: ServerMessageParameters;
};
export declare enum ServerMessageType {
    Information = 10,
    Error = 11,
    Modifications = 20,
    SharedDocumentsListChanged = 45,
    UserAccessListResponse = 100,
    SharedDocumentsListResponse = 101,
    AllUsersListResponse = 102,
    OpenSharedDocumentResponse = 103,
    StartSharedModeResponse = 104,
    StopSharedModeResponse = 105
}
export declare type StartSharedModeResponse = {
    modifications: ModificationsState;
    userAccess: UserAccess;
    userAccessList: UserAccess[];
};
export declare type StopSharedModeResponse = {};
export declare type ServerMessageParameters = ModificationsState | StartSharedModeResponse | StopSharedModeResponse | string;
