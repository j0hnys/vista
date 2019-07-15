import HelloType, { Dict, Enum, Tuple, List, Type, Rule, Self, IfExists } from 'hello-type'

const PersonType = new Type({
    name: String,
    age: Number,
    nested: [new Type({
        parameter_one: Number,
        parameter_two: Number
    })]
});


export default {
    namespace: 'ena/dyo/tria',
    assert(data) {
        PersonType.assert(data);
    },
};