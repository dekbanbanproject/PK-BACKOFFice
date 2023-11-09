import { DataStorage } from "../DataStorage/DataStorage";
export declare class SharedRef {
    viewer: any;
    moment: any;
    i18n: any;
    static onViewerInitialize(viewer: any, moment: any): void;
    static get(instanceId: string, getAny?: boolean): SharedRef;
    static dispose(instanceId: string): void;
    static get moment(): any;
    static get i18n(): any;
    static set i18n(i18n: any);
    static get count(): number;
    constructor(viewer: any, moment: any, i18n: any);
    get storage(): DataStorage;
    dispose(): void;
    releaseResources(): void;
}
