import { Model, Command } from './types';
export declare const init: () => Model;
export declare const reducer: (cmd: Command, model: Model) => Model;
