/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { ComboBoxProps } from '@grapecity/core-ui';
import { SharedAccessMode, UserAccess } from './types';
import { GcPdfViewer } from '../GcPdfViewer';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare type ShareDialogState = {
    accessMode: SharedAccessMode;
    enabled: boolean;
    fileName: string;
    showModal: boolean;
    userName: string;
    usersWithAccess?: UserAccess[] | null;
    allUserNames?: string[] | null;
};
export declare type ShareDialogProps = {
    title?: string;
};
export declare class ShareDialog extends Component<ShareDialogProps, ShareDialogState> {
    private _hidePromise?;
    private _resolve?;
    private _viewer;
    state: {
        enabled: boolean;
        fileName: string;
        showModal: boolean;
        userName: string;
        accessMode: SharedAccessMode;
        usersWithAccess: null;
        allUserNames: null;
    };
    private _disallowUnknownUsers?;
    private _knownUserNames?;
    readOptions(): void;
    show(viewer: GcPdfViewer): Promise<void>;
    hide(): void;
    render(): JSX.Element;
    renderAccessModeMenuTrigger(u: UserAccess, enabled: boolean): JSX.Element;
    private _resolveHidePromise;
    private _loadUserAccessList;
    private _loadAllUserNames;
    get in17n(): i18n;
    unshareDocument(userName: string): Promise<boolean>;
    shareDocument(userName: string, accessMode: SharedAccessMode): Promise<boolean>;
    getAccessModeComboBoxProps(userName: string, accessMode: SharedAccessMode): ComboBoxProps;
    getUserNamesSuggestions(): string[];
    getUsersComboBoxProps(): ComboBoxProps;
}
export declare function getAccessModeSvgIcon(accessMode: SharedAccessMode): JSX.Element;
export declare function getAccessModeDisplayText(accessMode: SharedAccessMode, in17n: i18n): string;
export declare function getAccessModeDescription(accessMode: SharedAccessMode, in17n: i18n): string;
