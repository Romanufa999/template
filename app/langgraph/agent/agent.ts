import {
  StateGraph,
  MessagesAnnotation,
  START,
  Annotation,
} from "@langchain/langgraph";
import { ToolNode } from "@langchain/langgraph/prebuilt";
import { ChatOpenAI } from "@langchain/openai";
import { tool } from "@langchain/core/tools";
import { z } from "zod";
import commands from "./commands.json";

function jsonSchemaToZod(schema: Record<string, any>): z.ZodObject<any> {
  const shape: Record<string, z.ZodTypeAny> = {};
  const properties = schema.properties || {};
  const required: string[] = schema.required || [];

  for (const [key, value] of Object.entries(properties) as [
    string,
    any,
  ][]) {
    let fieldSchema: z.ZodTypeAny;
    switch (value.type) {
      case "number":
        fieldSchema = z.number().describe(value.description || "");
        break;
      case "boolean":
        fieldSchema = z.boolean().describe(value.description || "");
        break;
      default:
        fieldSchema = z.string().describe(value.description || "");
    }
    shape[key] = required.includes(key) ? fieldSchema : fieldSchema.optional();
  }

  return z.object(shape);
}

const tools = commands.commands.map((cmd) => {
  const zodSchema = jsonSchemaToZod(cmd.parameters);
  return tool(
    async (input) => {
      return JSON.stringify({
        command: cmd.name,
        input,
        result: `[${cmd.name}] executed with input: ${JSON.stringify(input)}`,
      });
    },
    {
      name: cmd.name,
      description: cmd.description,
      schema: zodSchema,
    },
  );
});

const toolNode = new ToolNode(tools);

const llm = new ChatOpenAI({ model: "gpt-4o-mini", temperature: 0 }).bindTools(
  tools,
);

function shouldContinue(state: typeof GraphAnnotation.State) {
  const lastMessage = state.messages[state.messages.length - 1];
  if (
    "tool_calls" in lastMessage &&
    Array.isArray(lastMessage.tool_calls) &&
    lastMessage.tool_calls.length > 0
  ) {
    return "tools";
  }
  return "__end__";
}

const GraphAnnotation = Annotation.Root({
  messages: MessagesAnnotation.spec["messages"],
  timestamp: Annotation<number>,
});

const builder = new StateGraph(GraphAnnotation)
  .addNode("agent", async (state) => {
    const message = await llm.invoke([
      {
        type: "system",
        content:
          "You are a helpful assistant. " +
          "Use the available tools to help the user when appropriate.",
      },
      ...state.messages,
    ]);

    return { messages: message, timestamp: Date.now() };
  })
  .addNode("tools", toolNode)
  .addEdge(START, "agent")
  .addConditionalEdges("agent", shouldContinue, ["tools", "__end__"])
  .addEdge("tools", "agent");

export const graph = builder.compile();
