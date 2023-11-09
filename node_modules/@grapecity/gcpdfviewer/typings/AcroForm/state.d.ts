import { AcroFormsMsg, AcroFormModel } from './types';
export declare const init: () => AcroFormModel;
declare const reducer: (message: AcroFormsMsg, model: AcroFormModel) => AcroFormModel;
export { reducer as update };
