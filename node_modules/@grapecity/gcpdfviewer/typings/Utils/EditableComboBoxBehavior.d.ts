interface GetValueHelperSupport {
    getValue(name?: "value" | "textContent"): any;
}
export declare class EditableComboBoxBehavior implements GetValueHelperSupport {
    id: string;
    selectElement: HTMLSelectElement;
    storage: any;
    linkService: any;
    annotatationData: any;
    input: HTMLInputElement;
    constructor(id: string, selectElement: HTMLSelectElement, storage: any, linkService: any, annotatationData: any);
    setValueFromSelectElement(selectElement?: HTMLSelectElement): void;
    initialize(): boolean;
    setValue(value?: string): void;
    getValue(name?: "value" | "textContent"): any;
}
export {};
